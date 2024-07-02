<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appeal extends Model
{
    use HasFactory, Multitenant, SoftDeletes;

    protected $table = 'appeal';
    protected $fillable = [
        'employee_id',
        'attendance_id',
        'reason',
        'status',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id')->withTrashed();
    }

    public function attendance(){
        return $this->belongsTo(EmployeeAttendance::class, 'attendance_id')->withTrashed();
    }
}
