<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id', 'month_year', 'status'
    ];

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }
}
