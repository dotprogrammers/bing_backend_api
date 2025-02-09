<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Services\SmsService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;


use function Symfony\Component\String\b;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'mobile_number' => 'required|numeric|digits:11|unique:users,mobile_number',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $otp = rand(100000, 999999);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number,
                'sms_otp' => $otp,
                'sms_status' => false,
                'password' => bcrypt($request->password),
            ]);
            (new SmsService)->sendMessage($user->mobile_number, "Your verification code is $otp");

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

    public function mobileVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric',
            'otp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('mobile_number', $request->mobile_number)
            ->where('sms_otp', $request->otp)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid OTP or mobile number.',
            ], 401);
        }

        $user->update([
            'sms_status' => true,
            'sms_otp' => null,
        ]);
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'token' => $token,
            'message' => 'Mobile number verified successfully.',
        ]);
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

    public function sendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user_details,email',
        ]);

        $userDetail = UserDetail::where('email', $request->email)->first();

        if (!$userDetail) {
            return response()->json(['message' => 'User details not found.'], 404);
        }

        $user = User::find($userDetail->user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($userDetail->is_email_verified) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent.']);
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $userDetail = UserDetail::where('user_id', $id)->first();

        if (!$userDetail) {
            return response()->json(['message' => 'User details not found.'], 404);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if (!hash_equals(sha1($userDetail->email), $hash)) {
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }

        if ($userDetail->is_email_verified) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }

        $userDetail->update(['is_email_verified' => 1]);

        return response()->json(['message' => 'Email successfully verified.']);
    }
}
