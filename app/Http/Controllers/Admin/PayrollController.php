<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Payroll;
use App\Attendance;
use App\Http\Controllers\Controller;
use App\Leave;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class PayrollController extends Controller
{
    public function employeeList()
    {
        $month_years = $this->getMonthYears();
        
        if (request()->all()) {
            $employees = $this->getPayroll(request()->input('payroll_month'));
        } else {
            $employees = null;
        }

        $payrolls = Payroll::where('month_year', request()->input('payroll_month'))->get();
        
        if ($payrolls && $employees) {
            $isGenerated = count($employees) == count($payrolls) ? 'true' : 'false';
        } else {
            $isGenerated = 'empty';
        }
        
        
        $employer_no = 'E ';
        for ($i = 0; $i < 10; $i++) {
            $employer_no .= mt_rand(0, 9);
        }

        $data = [
            'isGenerated' => $isGenerated,
            'month_years' => array_reverse($month_years),
            'selected_payroll_month' => request()->input('payroll_month'),
            'employees' => $employees ? $employees : null,
            'employer_no' => $employer_no,
        ];

        return view('admin.payroll.employees')->with($data);
    }

    protected function getPayroll($payroll_month)
    {
        $employees = Employee::all();

        $start = (new Carbon($payroll_month))->format('Y-m-d');
        $end = (new Carbon('31 '.$payroll_month))->format('Y-m-d');

        $attendances = Attendance::where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->orderBy('created_at', 'asc')
            ->get();

        $employees->map(function ($employee) use ($attendances, $payroll_month) {
            $attendancesToday = $attendances->where('employee_id', $employee->id);
            $employee->attendancesToday = $attendancesToday;

            $totalEarnings = 0;
            $attendancesToday->map(function ($attendance) use (&$totalEarnings) {
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
        });

        return $employees;
    }

    protected function getMonthYears()
    {
        $employee = Employee::select('join_date')->orderBy('join_date', 'asc')->first();
        $join_date = $employee->join_date->toDateString();

        $firstMonth = intval(substr($join_date, 5, 2));
        $firstYear = intval(substr($join_date, 0, 4));

        $curr_date = Carbon::now()->toDateString();

        $lastMonth = intval(substr($curr_date, 5, 2));
        $lastYear = intval(substr($curr_date, 0, 4));

        $month_years = array();

        while ($firstYear <= $lastYear) {
            $month = 1;
            if ($firstYear == $lastYear) {
                while ($month <= $lastMonth) {
                    $date = '01-'.($month < 10 ? '0'.$month : $month).'-'.$firstYear;
                    $month_years[] = (new Carbon($date))->format('F Y');

                    $month++;
                }
            } else {
                while ($month <= 12) {
                    $date = '01-'.($month < 10 ? '0'.$month : $month).'-'.$firstYear;
                    $month_years[] = (new Carbon($date))->format('F Y');

                    $month++;
                }
            }

            $firstYear++;
        }

        return $month_years;
    }

    public function generate(Request $request)
    {
        $payroll = Payroll::create([
            'employee_id' => $request->employee_id,
            'month_year' => $request->payroll_month
        ]);

        $request->session()->flash('success', 'Payroll successfully generated.');
        return redirect()->back();
    }

    public function generateList(Request $request)
    {
        $selected_payroll_month = $request->selected_payroll_month;

        if (isset($request->employees_checkbox)) {
            $employees = Employee::whereIn('employees.id', $request->employees_checkbox)->get();
        } else {
            $employees = Employee::all();
        }

        foreach ($employees as $employee) {
            $payrolls = Payroll::where('employee_id', $employee->id)
                ->where('month_year', $selected_payroll_month)
                ->first();
            
            if ($payrolls === null) {
                $payroll = Payroll::create([
                    'employee_id' => $employee->id,
                    'month_year' => $selected_payroll_month
                ]);
            }
        };

        $request->session()->flash('success', 'Payroll successfully generated.');
        return redirect()->back();
    }

    public function update(Request $request, $leave_id)
    {
        $this->validate($request, [
            'status' => 'required'
        ]);
        $leave = Leave::find($leave_id);
        $leave->status = $request->status;
        $leave->save();
        $request->session()->flash('success', 'Leave status has been successfully updated');
        
        return back();
    }

    public function reportEmployees(){
        
        $month_years = $this->getMonthYears();

        $employees = Employee::all();
        $attendances = Attendance::all();

        foreach($month_years as $payroll_month){
            $employees->map(function ($employee) use ($attendances, $payroll_month) {
                $attendancesToday = $attendances->where('employee_id', $employee->id);
                $employee->attendancesToday = $attendancesToday;

                $totalEarnings = 0;
                $attendancesToday->map(function ($attendance) use (&$totalEarnings) {
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
            });
        }
        

        $data = [
            'employees' => $employees ? $employees : null,
        ];
        return view('admin.report.employees')->with($data);
    }
    

    public function reportMonth(){
        
        $month_years = $this->getMonthYears();
        $payrolls = [];

        $employees = Employee::all();
        $attendances = Attendance::all();
        
        foreach($month_years as $payroll_month){
            $employees->map(function ($employee) use ($attendances, $payroll_month) {
                $attendancesToday = $attendances->where('employee_id', $employee->id);
                $employee->attendancesToday = $attendancesToday;

                $totalEarnings = 0;
                $attendancesToday->map(function ($attendance) use (&$totalEarnings) {
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
            });
        }

        // dd($attendances);

        for($i = 0; $i < count($month_years); $i++){
            $total = 0;
            $payroll = [];

            foreach($attendances as $attendance){
                if(date('F Y', strtotime($attendance->created_at)) == $month_years[$i]){
                    $total += $attendance->earnings;
                }
            }

            $payroll['month'] = $month_years[$i];
            $payroll['payment'] = $total;

            array_push($payrolls, $payroll);
        }
        // dd($payrolls);
        $data = [
            'payrolls' => $payrolls ? $payrolls : null,
        ];
        return view('admin.report.month')->with($data);
    }
}
