<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\LeavesController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\LeaderController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\CalendarController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('register', [AuthController::class,'register']);
});
Route::group(['middleware' => 'api'], function ($router) {
    Route::get('profile', [AuthController::class,'me']);

    //route for attendance
    Route::get('attendance', [AttendanceController::class,'index']);
    Route::post('attendance/in', [AttendanceController::class,'attendanceCheckin']);
    Route::post('attendance/out', [AttendanceController::class,'attendanceCheckout']);
    Route::get('attendance/today', [AttendanceController::class,'checkAttendanceToday']);
    Route::get('attendance/forget', [AttendanceController::class,'attendanceForget']);
    Route::post('attendance/appeal', [AttendanceController::class,'createAppeal']);
    Route::get('attendance/forget3days', [AttendanceController::class,'attendanceForget3days']);
    Route::post('attendance/forget3days', [AttendanceController::class,'claimAttendanceForget3days']);

    //route for leave
    Route::get('leave', [LeavesController::class,'index']);
    Route::post('leave/submit', [LeavesController::class,'createLeaves']);
    Route::get('leave/track/{leave}', [LeavesController::class,'trackLeave']);

    //route for leader
    Route::get('leader/list-leave', [LeaderController::class,'list']);
    Route::post('leader/action/{leave}', [LeaderController::class,'action']);

    //route for home
    Route::get('home', [HomeController::class,'index']);
    Route::get('home/list', [HomeController::class,'listEmployee']);

    //route for announcement
    Route::get('announcement', [AnnouncementController::class,'index']);

    //Route for user
    Route::post('user/update', [EmployeeController::class,'updateProfile']);
    Route::post('user/update-avatar', [EmployeeController::class,'updateAvatar']);
    Route::post('user/update-password', [EmployeeController::class,'updatePassword']);

    //route for pin
    Route::post('pin/status', [EmployeeController::class,'setPinOn']);
    Route::post('pin/set', [EmployeeController::class,'setPin']);
    Route::post('pin/check', [EmployeeController::class,'checkPin']);
    Route::post('pin/checkPassword', [EmployeeController::class,'checkPassword']);

    //route for calendar
    Route::get('calendar', [CalendarController::class,'calendar']);
    Route::get('calendar/detail', [CalendarController::class,'calendarDetails']);
});
