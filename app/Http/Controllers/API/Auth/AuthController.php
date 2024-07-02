<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:employee-api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth('employee-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $employee = Employee::where('email', $credentials['email'])->first();
        if ($request->device_identifier != null && $employee->device_identifier == null) {
            $employee->update([
                'device_identifier' => $request->device_identifier
            ]);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $data = auth()->user();
        $data['department'] = $data->department;
        $data['designation'] = $data->designation;
        return response()->json($data);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('employee-api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('employee-api')->factory()->getTTL() * 60,

        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:employees',
            'password' => 'required',
            'phone' => 'required',
            'birthday' => 'required',
        ]);

        $employee = Employee::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'birthday' => $request->birthday,
            'is_flagged' => true,
        ]);

        $token = JWTAuth::fromUser($employee);

        return response()->json([
            'status' => "success",
            'data' => $employee,
            'token' => $token
        ]);
    }
}
