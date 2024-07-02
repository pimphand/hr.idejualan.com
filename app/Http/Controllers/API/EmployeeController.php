<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileEmployeeRequest;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee-api', ['except' => ['login']]);
    }

    public function updateProfile(UpdateProfileEmployeeRequest $request)
    {
        $employee = auth()->user();
        $validated = $request->validated();
        $employee->update([
            "firstname" => $validated['firstname'] ?? $employee->firstname,
            "lastname" => $validated['lastname'] ?? $employee->lastname,
            "phone" => $validated['phone'] ?? $employee->phone,
            "birthday" => $validated['birthday'] ?? $employee->birthday,
            "beliefs" => $validated['beliefs'] ?? $employee->beliefs,
            "marital_status" => $validated['marital_status'] ?? $employee->marital_status,
            "address" => $validated['address'] ?? $employee->address,
            "work_status" =>$validated['work_status'] ?? $employee->work_status,
            "gender" => $validated['gender'] ?? $employee->gender,
        ]);

        return response()->json([
            'status' => "success",
            'data' => $employee
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $employee = auth()->user();
        $imageName = null;
        if ($request->avatar != null) {
            $imageName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('storage/employees'), $imageName);
        }
        $employee->update([
            "avatar" => $imageName
        ]);

        return response()->json([
            'status' => "success",
            'data' => $employee
        ]);
    }

    public function updatePassword(Request $request)
    {
        if ($request->old_password == null || $request->new_password == null) {
            return response()->json([
                'status' => "false",
                'message' => "Password lama dan password baru tidak boleh kosong!"
            ]);
        }
        // dd(bcrypt($request->old_password));
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return response()->json([
                'status' => "false",
                'message' => "Password lama tidak cocok!"
            ]);
        }
        $employee = auth()->user();
        $employee->update([
            "password" => bcrypt($request->new_password)
        ]);
        return response()->json([
            'status' => "success",
            'data' => $employee
        ]);
    }

    public function setPinOn(Request $request)
    {
        $employee = auth()->user();
        $employee->update([
            "is_pin_active" => $request->is_pin_active
        ]);

        return response()->json([
            'status' => "success",
            'data' => $employee
        ]);
    }

    public function setPin(Request $request)
    {
        $employee = auth()->user();
        if ($employee->pin != null) {
            if ($request->old_pin != $employee->pin) {
                return response()->json([
                    'status' => "false",
                    'message' => "Old PIN is not same!"
                ]);
            }else{
                $employee->update([
                    "pin" => $request->pin
                ]);

                return response()->json([
                    'status' => "success",
                    'message' => "PIN has been changed!"
                ]);
            }
        } else if ($employee->pin == null) {
            $employee->update([
                "pin" => $request->pin
            ]);

            return response()->json([
                'status' => "success",
                'message' => "new pin has been set!",
            ]);
        }
    }

    public function checkPin(Request $request)
    {
        if ($request->pin == auth()->user()->pin) {
            return response()->json([
                'status' => "success",
                'message' => "PIN is same!",
                'data' => true
            ]);
        } else {
            return response()->json([
                'status' => "success",
                'message' => "PIN is not same!",
                'data' => false
            ]);
        }
    }

    public function checkPassword(Request $request)
    {
        if (Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'status' => "success",
                'message' => "Password is same!",
                'data' => true
            ]);
        } else {
            return response()->json([
                'status' => "success",
                'message' => "Password is not same!",
                'data' => false
            ]);
        }
    }
}
