<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Settings\AttendanceSettings;
use App\Models\Appeal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee-api', ['except' => ['login']]);
    }

    public function index(Request $request)
    {
        try {
            if ($request->get('type')) {
                $attendance = EmployeeAttendance::where('employee_id', auth()->user()->id)->where('type', $request->get('type'))->orderBy('created_at', 'desc')->get();
            } else {
                $attendance = EmployeeAttendance::where('employee_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'error' => $e->getMessage()], 500);
        }
        $attendance->transform(function ($item, $key) {
            if ($item->selfie_image) {
                $item->selfie_image = url('storage/attendees/' . $item->selfie_image);
            }
            return $item;
        });

        return response()->json([
            'status' => "success",
            'data' => $attendance
        ]);
    }

    public function attendanceCheckin(Request $request)
    {
        if (auth()->user()->device_identifier != $request->get('device_identifier') && auth()->user()->is_pin_active == 0) {
            return response()->json([
                'status' => "false",
                'message' => "Kunci sidik jari tidak cocok, permintaan ditolak!"
            ]);
        }
        $result = $this->checkTime("checkin");
        $imageName = null;
        if ($request->hasFile('selfie_image')) {
            $imageName = time() . '.' . $request->selfie_image->extension();
            Storage::disk('vultr')->put('public/storage/attendees/' . $imageName, file_get_contents($request->selfie_image));
            // $request->selfie_image->move(public_path('storage/attendees'), $imageName);
        }
        $attendance = EmployeeAttendance::create([
            'employee_id' => auth()->user()->id,
            'time' => date('H:i'),
            'type' => "MASUK",
            'status' => $result,
            'latitude' => request('latitude'),
            'longitude' => request('longitude'),
            'selfie_image' => $imageName,
            'address' => request('address'),
            'ip_address' => request()->ip(),
            'distance' => request('distance') ?? null,
            'permission_reason' => request('reason') ?? null,
            'status_by_hr' => request('distance') ? 'pending' : 'approved',
        ]);
        return response()->json([
            'status' => "success",
            'data' => $attendance
        ]);
    }

    public function attendanceCheckout(Request $request)
    {
        if ((auth()->user()->device_identifier != $request->get('device_identifier')) && (auth()->user()->device_identifier != null) && (auth()->user()->is_pin_active == 0)) {
            return response()->json([
                'status' => "false",
                'message' => "Kunci sidik jari tidak cocok, permintaan ditolak!"
            ]);
        }
        $result = $this->checkTime("checkout");
        $imageName = null;
        if ($request->selfie_image != null) {
            $imageName = time() . '.' . $request->selfie_image->extension();
            Storage::disk('vultr')->put('public/storage/attendees/' . $imageName, file_get_contents($request->selfie_image));
            // $request->selfie_image->move(public_path('storage/attendees'), $imageName);
        }
        $attendance = EmployeeAttendance::create([
            'employee_id' => auth()->user()->id,
            'time' => date('H:i'),
            'type' => "PULANG",
            'status' => $result,
            'latitude' => request('latitude'),
            'longitude' => request('longitude'),
            'selfie_image' => $imageName,
            'address' => request('address'),
            'ip_address' => request()->ip()
        ]);
        return response()->json([
            'status' => "success",
            'data' => $attendance
        ]);
    }

    private function checkTime($type)
    {
        $settings = new AttendanceSettings();
        $time = date('H:i');
        if ($type == "checkin") {
            $min_checkin_time = $settings->checkin_time;
            if ($time < $settings->checkin_time) {
                $result = 'Rajin';
            } elseif (($time >= $min_checkin_time) && ($time <= $settings->checkout_time)) {
                $result = 'Tepat Waktu';
            } else {
                $result = 'Terlambat';
            }
        } else {
            $row = EmployeeAttendance::where('employee_id', auth()->user()->id)->where('type', 'MASUK')->orderBy('created_at', 'desc')->first();
            $min_time = Carbon::parse($row->time)->addHour(8);
            if ($time < $min_time) {
                $result = 'Pulang Cepat';
            } elseif ($time < $min_time->addHour(1)) {
                $result = 'Tepat Waktu';
            } else {
                $result = 'Lembur';
            }
        }
        return $result;
    }

    public function checkAttendanceToday()
    {
        $attendance = EmployeeAttendance::where('employee_id', auth()->user()->id)->whereDate('created_at', date('Y-m-d'))->get();
        return response()->json([
            'status' => "success",
            'data' => $attendance
        ]);
    }

    public function attendanceForget()
    {
        //get all attendance on last 3 days
        $attendance = EmployeeAttendance::where('employee_id', auth()->user()->id)->get();
        $forgetAttendance = [];
        foreach ($attendance as $row) {
            $row->appeal;
            if ($row->type == "MASUK") {
                $checkout = EmployeeAttendance::where('employee_id', auth()->user()->id)->whereDate('created_at', $row->created_at)->where('type', 'PULANG')->first();
                if (!$checkout) {
                    array_push($forgetAttendance, $row);
                }
            }
        }
        return response()->json([
            'status' => "success",
            'data' => $forgetAttendance
        ]);
    }

    public function createAppeal(Request $request)
    {
        $request->validate([
            'reason' => 'required',
            'attendance_id' => 'required',
        ]);
        $appeal = new Appeal();
        $appeal->reason = $request->reason;
        $appeal->employee_id = auth()->user()->id;
        $appeal->attendance_id = $request->attendance_id;
        $appeal->status = "PENDING";
        $appeal->save();

        return response()->json([
            'status' => "success",
            'data' => $appeal
        ]);
    }

    public function attendanceForget3days()
    {
        $forgetAttendance = [];
        $date = Carbon::now()->subDays(3)->format('Y-m-d');
        for ($i = 0; $i <= 2; $i++) {
            $date = Carbon::parse($date)->addDay(1)->format('Y-m-d');
            $attendance = EmployeeAttendance::where('employee_id', auth()->user()->id)->whereDate('created_at', $date)->get();
            if ($attendance->isEmpty()) {
                array_push($forgetAttendance, $date);
            }
        }
        return response()->json([
            'status' => "success",
            'data' => $forgetAttendance
        ]);
    }

    public function claimAttendanceForget3days(Request $request)
    {
        $imageName = null;
        if ($request->selfie_image != null) {
            $imageName = time() . '.' . $request->selfie_image->extension();
            Storage::disk('vultr')->put('public/storage/attendees/' . $imageName, file_get_contents($request->selfie_image));
            // $request->selfie_image->move(public_path('storage/attendees'), $imageName);
        }
        $attendance = EmployeeAttendance::create([
            'employee_id' => auth()->user()->id,
            'time' => date('H:i'),
            'type' => "MASUK",
            'status' => "Lupa Absen",
            'latitude' => request('latitude'),
            'longitude' => request('longitude'),
            'selfie_image' => $imageName,
            'address' => request('address'),
            'ip_address' => request()->ip(),
            'created_at' => $request->date,
            'updated_at' => $request->date,
        ]);
        $appeal = new Appeal();
        $appeal->create([
            'reason' => "Lupa Absen",
            'employee_id' => auth()->user()->id,
            'attendance_id' => $attendance->id,
            'status' => "PENDING",
        ]);
        return response()->json([
            'status' => "success",
            'data' => $attendance
        ]);
    }
}
