<?php

namespace App\Http\Controllers\Employee;

use App\Payroll;
use App\Attendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SelfController extends Controller
{
    public function salary_slip()
    {
        $employee = Auth::user()->employee;

        $payroll_months = Payroll::where('employee_id', $employee->id)->get();
        
        if (request()->input('payroll_months')) {
            $employee = $this->getPayroll(request()->input('payroll_months'));
        } else {
            $employee = null;
        }
        
        $employer_no = 'E ';
        for ($i = 0; $i < 10; $i++) {
            $employer_no .= mt_rand(0, 9);
        }

        $data = [
            'employee' => $employee,
            'employer_no' => $employer_no,
            'payroll_months' => $payroll_months,
            'selected_payroll_month' => request()->input('payroll_months')
        ];

        return view('employee.self.salary')->with($data);
    }

    protected function getPayroll($payroll_month)
    {
        $employee = Auth::user()->employee;
        
        $start = (new Carbon($payroll_month))->format('Y-m-d');
        $end = (new Carbon('31 '.$payroll_month))->format('Y-m-d');

        $attendances = Attendance::where('created_at', '>=', $start)
            ->where('employee_id', $employee->id)
            ->where('created_at', '<=', $end)
            ->orderBy('created_at', 'asc')
            ->get();
            
        $employee->attendancesToday = $attendances;
        
        $totalEarnings = 0;
        $attendances->map(function ($attendance) use (&$totalEarnings) {
            $earnings = $attendance->rounds == 'half' ? 20 : 40;

            $totalEarnings += $earnings;

            $attendance->date = (new Carbon($attendance->created_at->toDateString()))->format('d/m/Y');
            $attendance->earnings = $earnings;
        });
        
        $payroll = Payroll::where('employee_id', $employee->id)
            ->where('month_year', $payroll_month)
            ->first();
        $employee->payroll = $payroll;
        $employee->total_earnings = $totalEarnings;

        return $employee;
    }

    public function salary_slip_print()
    {
        $employee = $this->getPayroll(request()->input('payroll_month'));

        $employer_no = 'E ';
        for ($i = 0; $i < 10; $i++) {
            $employer_no .= mt_rand(0, 9);
        }

        $data = [
            'employee' => $employee,
            'employer_no' => $employer_no,
            'selected_payroll_month' => request()->input('payroll_month')
        ];

        return view('employee.self.salary-print')->with($data);
    }
}
