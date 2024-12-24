<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

use function Symfony\Component\String\b;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $user_role = Role::where('name', 'vendor')->first();
            if ($user_role) {
                $user->assignRole($user_role);
            }

            $token = $user->createToken('API Token')->plainTextToken;

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Registration successful',
                'token' => $token,
                'role' => $user->roles->pluck('name'),
            ]);

        } catch (\Exception $e) {
            // throw $e;
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'An error occurred during registration. Please try again later.',
            ], 500);
        }
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'token' => $token,
                'role' => Auth::user()->roles->pluck('name') ?? [],
            ]);
        }

        return response()->json([
            'message' => 'Invalid login credentials'
        ], 401);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Logout successful'
            ]);
        } catch (\Exception $e) {
            // throw $e;
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong'
            ]);
        }
    }
}
