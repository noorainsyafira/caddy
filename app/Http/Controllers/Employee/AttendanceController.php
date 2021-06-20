<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

use App\Attendance;
use App\Holiday;
use App\Rules\DateRange;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use phpDocumentor\Reflection\Location;

class AttendanceController extends Controller
{
    // Opens view for attendance register form
    public function create(Request $request)
    {
        $employee = Auth::user()->employee;

        $data = [
            'employee' => $employee,
        ];

        return view('employee.attendance.create')->with($data);
    }

    // Stores entry record of attendance
    public function store(Request $request, $employee_id)
    {
        $curr_attendance = Attendance::where('employee_id', $employee_id)
            ->where('shift', $request->attendance_shift)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($curr_attendance) {
            $request->session()->flash('error', 'Attendance entry failed. You have already checked in this '.$request->attendance_shift.'.');
        } else {
            Attendance::create([
                'employee_id' => $employee_id,
                'shift' => $request->attendance_shift,
                'rounds' => $request->attendance_rounds
            ]);

            $request->session()->flash('success', 'Attendance entry successfully logged');
        }

        return redirect()->route('employee.attendance.create')->with('employee', Auth::user()->employee);
    }

    public function index()
    {
        $employee = Auth::user()->employee;
        $attendances = $employee->attendances;
        $filter = false;
        if (request()->all()) {
            $this->validate(request(), ['date_range' => new DateRange]);
            $filter = true;
            [$start, $end] = explode(' - ', request()->input('date_range'));
            
            if ($attendances) {
                $start = Carbon::parse($start);
                $end = Carbon::parse($end);
                
                $attendances = $this->attendanceOfRange($attendances, $start, $end);
            }
        }
        if ($attendances) {
            $attendances = $attendances->reverse()->values();
        }

        $data = [
            'employee' => $employee,
            'attendances' => $attendances,
            'filter' => $filter,
            'date_range' => request()->input('date_range'),
        ];

        return view('employee.attendance.index')->with($data);
    }

    public function attendanceOfRange($attendances, $start, $end)
    {
        return $attendances->filter(function ($attendance, $key) use ($start, $end) {
            $date = Carbon::parse($attendance->created_at);
            if ((intval($date->dayOfYear) >= intval($start->dayOfYear)) && (intval($date->dayOfYear) <= intval($end->dayOfYear))) {
                return true;
            }
        })->values();
    }
}
