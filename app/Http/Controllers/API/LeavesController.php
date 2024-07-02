<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\LeaveType;

class LeavesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee-api', ['except' => ['login']]);
    }

    public function index(Request $request){
        try {
            if($request->get('status')){
                $leaves = Leave::where('employee_id', auth()->user()->id)->where('status', $request->get('status'))->orderBy('created_at', 'desc')->get();
            } else {
                $leaves = Leave::where('employee_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
            }
            foreach($leaves as $leave){
                $leave->leave_type = LeaveType::find($leave->leave_type_id);
            }
        } catch(\Exception $e) {
            return response()->json(['status' => 'false','error' => $e->getMessage()], 500);
        }
        return response()->json([
            'status' => "success",
            'data' => $leaves,
            'available_leaves' => LeaveType::all()
        ]);
    }

    public function createLeaves(Request $request){
        $leaves = Leave::whereHas('employee', function($q){
            $q->where('department_id', auth()->user()->department_id);
        })->whereBetween('from', [$request->get('start_date'), $request->get('end_date')])->orWhereBetween('to', [$request->get('start_date'), $request->get('end_date')])->get();
        if($leaves->count() > 4){
            return response()->json([
                'status' => "false",
                'message' => "Jumlah cuti pada tanggal tersebut sudah penuh, silahkan pilih tanggal lain!"
            ]);
        }
        $leave = Leave::create([
            'employee_id' => auth()->user()->id,
            'leave_type_id' => request('leave_type_id'),
            'from' => request('start_date'),
            'to' => request('end_date'),
            'reason' => request('reason'),
            'status' => "PENDING"
        ]);
        return response()->json([
            'status' => "success",
            'data' => $leave
        ]);
    }

    public function trackLeave(Leave $leave){
        return response()->json([
            'status' => "success",
            'data' => $leave
        ]);
    }
}
