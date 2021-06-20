<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Leave;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::all();
        $leaves = $leaves->map(function ($leave, $key) {
            $employee = Employee::find($leave->employee_id);
            $leave->employee = $employee;
            return $leave;
        });
        return view('admin.leaves.index')->with('leaves', $leaves);
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
}
