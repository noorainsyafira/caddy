<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    protected $fillable = ['employee_id', 'shift', 'rounds', 'created_at', 'updated_at'];
    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }
}
