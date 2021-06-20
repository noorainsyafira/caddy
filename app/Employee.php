<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $dates = ['created_at', 'dob','updated_at', 'join_date'];
    protected $fillable = ['user_id', 'first_name', 'last_name', 'ic', 'sex', 'dob', 'join_date', 'nationality', 'photo'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function attendances()
    {
        return $this->hasMany('App\Attendance')->orderBy('created_at', 'asc');
    }

    public function leave()
    {
        return $this->hasMany('App\Leave');
    }

    public function expense()
    {
        return $this->hasMany('App\Expense');
    }

    public function payrolls()
    {
        return $this->hasMany('App\Payroll');
    }
}
