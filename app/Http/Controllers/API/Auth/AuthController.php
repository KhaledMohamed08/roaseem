<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\Otp;
use App\Models\User;
use App\Services\OTPServiceOnlyPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Start
    public function generateOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|unique:otps,phone',
        ]);
        $phone = $request['phone'];
        $otpService = new OTPServiceOnlyPhone;
        $otp = $otpService->generateOTP($phone, 10);
        if ($otp) {
            return ApiResponse::success(
                [
                    'phone' => $phone,
                    'otp' => $otp,
                ],
                'OTP Sent Successfully',
                200
            );
        } else {
            return ApiResponse::error(
                'Failed OTP Send',
                400,
            );
        }
    }

    public function regenerateOTP(Request $request){
        $request->validate([
            'phone' => 'required|numeric',
        ]);
        $phone = $request['phone'];
        $otpService = new OTPServiceOnlyPhone();
        $otp = $otpService->regenerateOTP($phone);

        return ApiResponse::success(
            [
                'phone' => $phone,
                'otp' => $otp,
            ],
            'OTP Regenrated Successfully',
            200
        );
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
            'otp' => 'required|numeric',
        ]);
        $credentials = $request->only('phone', 'otp');
        $otpService = new OTPServiceOnlyPhone();
        $verfied = $otpService->verifyOTP($credentials['phone'], $credentials['otp']);
        if ($verfied) {
            return ApiResponse::success(
                [
                    'phone' => $credentials['phone']
                ],
                'OTP Verified Successfully',
                200
            );
        } else {
            return ApiResponse::error(
                'Wrong, Expired or Used OTP',
                400
            );
        }
    }

    public function register(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        $otp = Otp::where('phone', $request['phone'])->first();
        if ($otp->used_at != null) {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'password' => $validatedData['password'],
            ]);
            $otp->update([
                'user_id' => $user->id,
            ]);
            $token = $user->createToken('user_token')->plainTextToken;

            return ApiResponse::success(
                [
                    'user' => new UserResource($user),
                    'token' => $token
                ],
                'User Created Successfully',
                200,
            );
        }

        return ApiResponse::error(
            'This Phone Number Is Not Verified!',
            400,
        );
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('user_token')->plainTextToken;

            return ApiResponse::success(
                [
                    'user' => new UserResource($user),
                    'token' => $token,
                ],
                'User Logedin Successfully',
                200
            );
        }

        return ApiResponse::error(
            'Invalid email or password',
            401
        );
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    // public function forgoetPassword()
    // {

    // }
}
