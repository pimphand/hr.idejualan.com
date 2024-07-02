<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;

class LeaderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:employee-api', ['except' => ['login']]);
    }

    public function list(){
        if(auth()->user()->role != 'leader'){
            return response()->json(['status' => 'false','error' => 'You are not a leader'], 500);
        }
        $leaves = Leave::whereHas('employee', function($query){
            $query->where('department_id', auth()->user()->department_id);
        })->where('status_by_leader', 'Pending')->with('LeaveType')->orderBy('created_at', 'desc')->get();

        foreach($leaves as $leave){
            $leave->employee;
        }

        return response()->json([
            'status' => "success",
            'data' => $leaves
        ]);
    }

    public function action(Leave $leave, Request $request){
        if(auth()->user()->role != 'leader'){
            return response()->json(['status' => 'false','error' => 'You are not a leader'], 500);
        }

        $leave->status_by_leader = $request->status;
        $leave->save();

        return response()->json([
            'status' => "success",
            'data' => $leave
        ]);
    }
}
