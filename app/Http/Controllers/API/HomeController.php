<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee-api', ['except' => ['login']]);
    }

    public function index(){
        
        $checkout = EmployeeAttendance::where('employee_id', auth()->user()->id)->where('type', 'PULANG')->count();
        $forgetCheckout = EmployeeAttendance::where('employee_id', auth()->user()->id)->where('type', 'MASUK')->count() - $checkout;
        return response()->json([
            'status' => "success",
            'data' => [
                'totalAttendance' => $checkout,
                'forgetCheckout' => $forgetCheckout
            ]
        ]);
    }

    public function listEmployee(){
        $day = date('Y-m-d');
        $leaves = Leave::where('status', 'Approved')->where('from','<=',$day)->where('to','>=',$day)->get();
        foreach($leaves as $leave){
            $leave->employee;
            $leave->leaveType;

        }
        $notAttendanceToday = Employee::with('designation')->whereNotIn('id',EmployeeAttendance::where('type','MASUK')->whereDate('created_at',date('Y-m-d'))->pluck('employee_id'))->get();
        $attendanceToday = Employee::with('designation')->whereIn('id',EmployeeAttendance::where('type','MASUK')->whereDate('created_at',date('Y-m-d'))->pluck('employee_id'))->get();
        $birthdayToday = Employee::with('designation')->whereMonth('birthday',date('m'))->whereDay('birthday',date('d'))->get();
        $birthdayTomorrow = Employee::with('designation')->whereMonth('birthday',date('m'))->whereDay('birthday',date('d')+1)->get();
        return response()->json([
            'status' => "success",
            'data' => [
                'leaves' => $leaves,
                'notCheckedInToday' => $notAttendanceToday,
                'checkedInToday' => $attendanceToday,
                'birthdayToday' => $birthdayToday,
                'birthdayTomorrow' => $birthdayTomorrow
            ]
        ]);
    }
}
