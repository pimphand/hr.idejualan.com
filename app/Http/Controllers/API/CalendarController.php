<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;


class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee-api');
    }

    public function calendar(Request $request){
        $leaves = DB::table('leaves')
        ->select(DB::raw("DATE_FORMAT(`from`, '%Y-%m-%d') as date"),
                DB::raw("count(*) as total"))
        ->whereRaw("DATE_FORMAT(`from`, '%Y-%m') = ?", [$request->month])
        ->where('business_id', auth()->user()->business_id)
        ->groupBy('date')
        ->get()
        ->pluck('total', 'date')
        ->toArray();

        $birthday = Employee::select(DB::raw("DATE_FORMAT(`birthday`, '%Y-%m-%d') as date"),
                DB::raw("count(*) as total"))
        ->whereRaw("DATE_FORMAT(`birthday`, '%m') = ?", [substr($request->month, 5, 2)])
        ->where('business_id', auth()->user()->business_id)
        ->groupBy('date')
        ->get()
        ->pluck('total', 'date')
        ->toArray();

        $birthday = array_combine(array_map(function($key) {
            return substr(date('Y'), 0, 4).substr($key, 4);
        }, array_keys($birthday)), $birthday);


        return response()->json([
            'message' => 'success',
            'data' => [
                'leaves' => $leaves,
                'birthday' => $birthday
            ]
        ]);
    }

    public function calendarDetails(Request $request){
        $leaves = Leave::with('employee','leaveType')->where('from', '<=', $request->date)
                        ->where('to', '>=', $request->date)
                        ->get();
        $birthday = Employee::where('business_id', auth()->user()->business_id)->whereRaw("DATE_FORMAT(`birthday`, '%m-%d') = ?", [substr($request->date, 5, 5)])
                        ->get();
        return response()->json([
            'message' => 'success',
            'data' => [
                'leaves' => $leaves,
                'birthday' => $birthday
            ]
        ]);
    }
}
