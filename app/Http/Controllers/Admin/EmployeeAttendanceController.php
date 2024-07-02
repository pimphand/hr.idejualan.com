<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Http\Controllers\Controller;
use App\Settings\AttendanceSettings;
use App\Exports\AttendeeExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class EmployeeAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'employee attendance';
        $attendances = EmployeeAttendance::latest();

        if ($request->start_date && $request->end_date) {
            // Assuming you have a 'date' column in your EmployeeAttendance model
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();

            $attendances->whereBetween('created_at', [$start_date, $end_date]);
        }

        if ($request->type) {
            $attendances->where("type", $request->type);
        }

        $queryParams = $request->except('page');
        $attendances = $attendances->paginate(25)->appends($queryParams);
        return view('backend.attendance', compact(
            'title',
            'attendances'
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
        $this->validate($request, [
            'employee' => 'required',
            'checkin' => 'required',
        ]);
        $settings = new AttendanceSettings();
        $time = date('H:i');
        if ($request->type == "MASUK") {
            $min_checkin_time = strtotime($settings->checkin_time) + 1800;
            if ($time < $settings->checkin_time) {
                $status = 'Rajin';
            } else if ($time <= date('H:i', $min_checkin_time)) {
                $status = 'Tepat Waktu';
            } else {
                $status = 'Terlambat';
            }
        }
        if ($request->type == "PULANG") {
            $min_checkout_time = strtotime($settings->checkout_time) + 1800;
            if ($time < $settings->checkout_time) {
                $status = 'Pulang Cepat';
            } else if ($time <= date('H:i', $min_checkout_time && $time > $settings->checkout_time)) {
                $status = 'Tepat Waktu';
            } else {
                $status = 'Lembur';
            }
        }
        EmployeeAttendance::create([
            'employee_id' => $request->employee,
            'time' => $request->checkin,
            'type' => $request->type,
            'status' => $status,
        ]);
        $notification = notify('Presensi Berhasil Dibuat');
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
        $this->validate($request, [
            'employee' => 'required',
            'checkin' => 'required',
        ]);
        $settings = new AttendanceSettings();
        $time = date('H:i');
        $min_checkin_time = strtotime($settings->checkin_time) + 1800;
        if ($request->checkin) {
            if ($time < $settings->checkin_time) {
                $status = 'Rajin';
            }
            if ($time <= date('H:i', $min_checkin_time)) {
                $status = 'Tepat Waktu';
            } else {
                $status = 'Terlambat';
            }
        }
        if ($request->type == "PULANG") {
            if ($time < $settings->checkout_time) {
                $status = 'Rajin';
            }
            if ($time >= date('H:i', $min_checkin_time)) {
                $status = 'Tepat Waktu';
            } else {
                $status = 'Lembur';
            }
        }
        $attendance = EmployeeAttendance::findOrFail($request->id);
        $attendance->update([
            'employee_id' => $request->employee,
            'checkin' => $request->checkin,
            'checkout' => $request->checkout,
            'status' => $status,
        ]);
        $notification = notify('employee attendance has been updated');
        return back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        EmployeeAttendance::findOrFail($request->id)->delete();
        $notification = notify('employee attendance has been deleted');
        return back()->with($notification);
    }

    public function outside(Request $request)
    {
        $title = 'employee attendance';
        $query = EmployeeAttendance::select(
                'id',
                'time',
                'address',
                'permission_reason',
                'distance',
                'employee_id',
                'status_by_hr',
                'created_at'
        )->with('employee')->whereNotNull('distance');
        if ($request->start_date && $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('created_at', [$start_date, $end_date]);
        }
        $attendances = $query->get();
        return view('backend.attendance-outside', compact(
            'title',
            'attendances'
        ));
    }

    public function setApproved(EmployeeAttendance $attendance)
    {
        $attendance->status_by_hr = "approved";
        $attendance->save();
        return response()->json([
            'status' => "success",
            'data' => $attendance
        ]);
    }

    public function setRejected(EmployeeAttendance $attendance)
    {
        $attendance->status_by_hr = "rejected";
        $attendance->save();
        return response()->json([
            'status' => "success",
            'data' => $attendance
        ]);
    }

    public function export()
    {
        return Excel::download(new AttendeeExport, 'Rekapitulasi Presensi Pegawai.xlsx');
    }
}
