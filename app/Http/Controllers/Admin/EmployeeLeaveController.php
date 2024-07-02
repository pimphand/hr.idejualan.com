<?php

namespace App\Http\Controllers\Admin;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\LeavesExport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "employee leave";
        $leaves = Leave::where('status_by_leader','Approved')->with('leaveType','employee')->orderBy('id','desc')->get();
        $leave_types = LeaveType::get();
        $employees = Employee::get();
        return view('backend.employee-leaves',compact(
            'title','leaves','leave_types','employees'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'employee'=>'required',
            'leave_type'=>'required',
            'from'=>'required',
            'to'=>'required',
            'reason'=>'required'
        ]);
        Leave::create([
            'employee_id'=>$request->employee,
            'leave_type_id'=>$request->leave_type,
            'from'=>$request->from,
            'to'=>$request->to,
            'reason'=>$request->reason,
            'status' =>$request->status,
        ]);
        $notification = notify("Employee leave has been added");
        return back()->with($notification);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $leave = Leave::find($request->id);
        $leave->update([
            'employee_id'=>$request->employee,
            'leave_type_id'=>$request->leave_type,
            'from'=>$request->from,
            'to'=>$request->to,
            'reason'=>$request->reason,
            'status' => $request->status,
        ]);
        $notification = notify("Employee leave has been updated");;
        return back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $leave = Leave::find($request->id);
        $leave->delete();
        $notification = notify('Employee leave has been deleted');
        return back()->with($notification);
    }

    public function export(){
        return Excel::download(new LeavesExport, 'REKAPITULASI IZIN PEGAWAI per '.now()->format('Y-m-d').'.xlsx');
    }
}
