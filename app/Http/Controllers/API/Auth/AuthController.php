<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\FcmToken;
use App\Models\Otp;
use App\Models\User;
use App\Services\FcmToken as ServicesFcmToken;
// use App\Services\OTPService;
use App\Services\OTPServiceOnlyPhone;
use App\Services\SendSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $fcmToken;
    protected $sendSms;

    public function __construct(ServicesFcmToken $fcmToken, SendSms $sendSms)
    {
        $this->fcmToken = $fcmToken;
        $this->sendSms = $sendSms;
    }

    // Start
    public function generateOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|unique:otps,phone',
        ]);
        $phone = $request['phone'];
        $otpService = new OTPServiceOnlyPhone;
        $otp = $otpService->generateOTP($phone, 10);
        $message = "مرحبا بكم في منصة رواسيم العقارية كود:" . $otp ;
        $sendSms = $this->sendSms->sendSms("966" . $phone, $message);

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

    public function regenerateOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
        ]);
        $phone = $request['phone'];
        $otpService = new OTPServiceOnlyPhone();
        $otp = $otpService->regenerateOTP($phone);
        $message = "مرحبا بكم في منصة رواسيم العقارية كود:" . $otp ;
        $sendSms = $this->sendSms->sendSms("966" . $phone, $message);

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
        $otp = Otp::where('phone', $validatedData['phone'])->first();
        if ($otp && $otp->used_at != null) {
            $user = User::create($validatedData);
            $token = $user->createToken('user_token')->plainTextToken;
            if($validatedData['fcmToken'])
            {
                $fcmToken = $this->fcmToken->fcmSave($validatedData['fcmToken'], $user->id);
            }
        } else {
            return ApiResponse::error(
                'Phone Number Not Verified!',
                400
            );
        }

        return ApiResponse::success(
            [
                'user' => new UserResource($user),
                'token' => $token,
            ],
            'User Created Successfully',
            200
        );
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        if (!is_numeric($credentials['phone'])) {
            $credentials = ['email' => $credentials['phone'], 'password' => $credentials['password']];
        }

        if (Auth::attempt($request->only(['phone', 'password']))) {   
            $userId = Auth::id();
            $user = User::find($userId);
            $token = $user->createToken('user_token')->plainTextToken;

            //Notification_fireBase
            if($request->fcmToken)
            {
                $fcmToken = $this->fcmToken->fcmSave($request->fcmToken, $user->id);
            }

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
            'Invalid phone, email or password',
            401
        );
    }

    public function logout(Request $request)
    {
        $userId = $request->user()->id;
        if($request->fcmToken)
        {
            $fcmToken = $this->fcmToken->fcmDelete($request->fcmToken, $userId);
        }
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function forgoetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required',
        ]);
        $otpService = new OTPServiceOnlyPhone();
        $otp = $otpService->regenerateOTP($validatedData['phone'], 10);
        $sendSms = $this->sendSms->sendSms("966" . $validatedData['phone'], $otp);
        $message = "مرحبا بكم في منصة رواسيم العقارية كود:" . $otp ;
        $phone = $validatedData['phone'];

        return ApiResponse::success(
            [
                'phone' => $phone,
                'otp' => $message,
            ],
            'OTP Sent Successfully',
            200,
        );
    }

    public function updatePassword(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required|numeric',
            'password' => 'required|min:6|confirmed'
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = User::where('phone', $validatedData['phone'])->first();
        $user->update([
            'password' => $validatedData['password'],
        ]);

        return ApiResponse::successWithoutData(
            'Password Updated Successfully',
            200
        );
    }

    public function fcmToken(Request $request)
    {
        $user = Auth::user();

        if($request->fcmToken)
        {
            $fcmToken = $this->fcmToken->fcmSave($request->fcmToken, $user->id);
        }
    }
}