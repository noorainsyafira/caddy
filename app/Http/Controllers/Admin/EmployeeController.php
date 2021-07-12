<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\Employee;
use App\Http\Controllers\Controller;
use App\Role;
use App\Payroll;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;

use function Ramsey\Uuid\v1;

class EmployeeController extends Controller
{
    public function index()
    {
        $data = [
            'employees' => Employee::all()
        ];
        return view('admin.employees.index')->with($data);
    }
    public function create()
    {
        $data = [
            'desgs' => ['Caddy Master', 'Caddy']
        ];
        return view('admin.employees.create')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'ic' => 'required',
            'sex' => 'required',
            'email' => 'required|email',
            'photo' => 'image|nullable',
            'password' => 'required|confirmed|min:6'
        ]);

        // DB::beginTransaction();
        // try {
        $user = User::create([
                'name' => $request->first_name.' '.$request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

        $employeeRole = Role::where('name', 'employee')->first();
        $user->roles()->attach($employeeRole);
        $employeeDetails = [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'ic' => $request->ic,
                'sex' => $request->sex,
                'dob' => $request->dob,
                'join_date' => $request->join_date,
                'nationality' => $request->nationality,
                'photo'  => 'user.png'
            ];

        // Photo upload
        if ($request->hasFile('photo')) {
            // GET FILENAME
            $filename_ext = $request->file('photo')->getClientOriginalName();
            // GET FILENAME WITHOUT EXTENSION
            $filename = pathinfo($filename_ext, PATHINFO_FILENAME);
            // GET EXTENSION
            $ext = $request->file('photo')->getClientOriginalExtension();
            //FILNAME TO STORE
            $filename_store = $filename.'_'.time().'.'.$ext;
            // UPLOAD IMAGE
            $path = $request->file('photo')->storeAs('public'.DIRECTORY_SEPARATOR.'employee_photos', $filename_store);
            // add new file name
            $image = $request->file('photo');
            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(300, 300);
            $image_resize->save(public_path(DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'employee_photos'.DIRECTORY_SEPARATOR.$filename_store));
            $employeeDetails['photo'] = $filename_store;
        }
            
        $employee = Employee::create($employeeDetails);
            
        $request->session()->flash('success', 'Employee has been successfully added');
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     $request->session()->flash('error', 'Employee failed to add');
        // }
        
        return back();
    }

    public function attendance(Request $request)
    {
        $data = [
            'date' => null
        ];
        if ($request->all()) {
            $date = Carbon::create($request->date);
            $employees = $this->attendanceByDate($date);
            $data['date'] = $date->format('d M, Y');
        } else {
            $employees = $this->attendanceByDate(Carbon::now());
        }

        $data['employees'] = $employees;

        return view('admin.employees.attendance')->with($data);
    }

    public function attendanceByDate($date)
    {
        $employees = DB::table('employees')->select('id', 'first_name', 'last_name')->get();
        $attendances = Attendance::all()->filter(function ($attendance, $key) use ($date) {
            return $attendance->created_at->toDateString() == $date->toDateString();
        });

        return $employees->map(function ($employee, $key) use ($attendances) {
            $attendancesToday = $attendances->where('employee_id', $employee->id);
            $employee->attendancesToday = $attendancesToday;
            return $employee;
        });
    }

    public function destroy($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $user = User::findOrFail($employee->user_id);
        // detaches all the roles
        DB::table('leaves')->where('employee_id', '=', $employee_id)->delete();
        DB::table('attendances')->where('employee_id', '=', $employee_id)->delete();
        DB::table('expenses')->where('employee_id', '=', $employee_id)->delete();
        $employee->delete();
        $user->roles()->detach();
        // deletes the users
        $user->delete();
        request()->session()->flash('success', 'Caddy record has been successfully deleted');
        return back();
    }

    public function attendanceDelete($attendance_id)
    {
        $attendance = Attendance::findOrFail($attendance_id);
        $attendance->delete();
        request()->session()->flash('success', 'Attendance record has been successfully deleted!');
        return back();
    }

    public function employeeProfile($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        return view('admin.employees.profile')->with('employee', $employee);
    }

    public function salary_slip_print()
    {
        $employee = $this->getPayroll(request()->input('payroll_month'), request()->input('employee_id'));
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

    protected function getPayroll($payroll_month, $employee_id)
    {
        $employee = Employee::find($employee_id);
        
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
}
