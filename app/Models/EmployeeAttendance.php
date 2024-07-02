<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeAttendance extends Model
{
    use HasFactory, Multitenant, SoftDeletes;

    protected $fillable = [
        'employee_id','time','type','status','latitude','longitude','selfie_image','address','ip_address', 'distance', 'permission_reason','status_by_hr','created_at','updated_at'
    ];

    public function employee(){
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    public function appeal(){
        return $this->hasOne(Appeal::class,'attendance_id');
    }
}
