<?php

use App\Attendance;
use App\Department;
use \DateTime as DateTime;
use App\Role;
use App\User;
use App\Employee;
use App\Payroll;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeRole = Role::where('name', 'employee')->first();
        $adminRole =  Role::where('name', 'admin')->first();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin')
        ]);
        $admin->roles()->attach($adminRole);

        $employee = User::create([
            'name' => 'Noorain Syafira',
            'email' => 'noorainsyafira.sf@gmail.com',
            'password' => Hash::make('fyraa')
        ]);
        $employee->roles()->attach($employeeRole);
        
        $dob = new DateTime('1997-09-15');
        $join = new DateTime('2020-01-15');
        $employee = Employee::create([
            'user_id' => $employee->id,
            'first_name' => 'Noorain',
            'last_name' => 'Syafira',
            'ic' => '970915105443',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Female',
            'nationality' => 'Malaysian',
            'join_date' => $join->format('Y-m-d'),
            'photo' => 'fira2_1617597666.jpg'
        ]);

        // Attendance seeder
        $attendances = factory(App\Attendance::class, 10)->create([
            'employee_id' => $employee->id
        ]);

        foreach ($attendances as $index => $attendance) {
            $date = $attendance->created_at->toDateTimeString();
            $month = intval(substr($date, 5, 2))-1;
            $monthStr = $month<10 ? '0'.$month : $month;
            $dayStr = ($index+1) < 10 ? '-0'.($index+1) : '-'.($index+1);
            $newDate = substr($date, 0, 5) . $monthStr . $dayStr . substr($date, 10);
            
            $attendance->update([
                'created_at' => $newDate,
                'updated_at' => $newDate
            ]);
        }

        //Payroll Seeder
        $payroll = Payroll::create([
            'employee_id' => $employee->id,
            'month_year' => 'May 2021'
        ]);

        $employee = User::create([
            'name' => 'Test Test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('test')
        ]);
        $employee->roles()->attach($employeeRole);

        $dob = new DateTime('1997-11-28');
        $join = new DateTime('2020-05-01');
        $employee = Employee::create([
            'user_id' => $employee->id,
            'first_name' => 'Test',
            'last_name' => 'Test',
            'ic' => '971020142333',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Male',
            'nationality' => 'Malaysian',
            'join_date' => $join->format('Y-m-d'),
            'photo' => 'fira2_1617597666.jpg'
        ]);

        // Attendance seeder
        $attendances = factory(App\Attendance::class, 10)->create([
            'employee_id' => $employee->id
        ]);

        foreach ($attendances as $index => $attendance) {
            $date = $attendance->created_at->toDateTimeString();
            $month = intval(substr($date, 5, 2))-1;
            $monthStr = $month<10 ? '0'.$month : $month;
            $dayStr = ($index+1) < 10 ? '-0'.($index+1) : '-'.($index+1);
            $newDate = substr($date, 0, 5) . $monthStr . $dayStr . substr($date, 10);
            
            $attendance->update([
                'created_at' => $newDate,
                'updated_at' => $newDate
            ]);
        }

        //Payroll Seeder
        $payroll = Payroll::create([
            'employee_id' => $employee->id,
            'month_year' => 'June 2021'
        ]);
    }
}
