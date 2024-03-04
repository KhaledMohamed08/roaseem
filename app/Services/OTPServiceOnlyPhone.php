<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;

class OTPServiceOnlyPhone
{
    public function generateOTP(string $phone, int $expirationMinutes = 10, Carbon $carbon = null): ?string
    {
        // Use a more secure method to generate OTP
        $otp = random_int(1000, 9999);

        // Use dependency injection for Carbon
        $expiresAt = ($carbon ?: Carbon::now())->addMinutes($expirationMinutes);

        // Specify the phone column when creating the Otp record
        $otpRecord = Otp::create([
            'code' => $otp,
            'expires_at' => $expiresAt,
            'user_type' => User::class,
            'phone' => $phone,
        ]);

        return $otpRecord->code;
    }

    public function regenerateOTP($phone, int $expirationMinutes = 10, Carbon $carbon = null): ?string
    {
        // Use a more secure method to generate OTP
        $code = random_int(1000, 9999);

        // Use dependency injection for Carbon
        $expiresAt = ($carbon ?: Carbon::now())->addMinutes($expirationMinutes);

        $OTP = Otp::where('phone', $phone)->first();

        $OTP->update([
            'code' => $code,
            'expires_at' => $expiresAt,
            'used_at' => null,
        ]);

        return $OTP->code;
    }

    public static function verifyOTP($phone, string $otp): bool
    {
        $otpRecord = Otp::where('user_type', User::class)
            ->where('phone', $phone)
            ->where('code', $otp)
            ->where('expires_at', '>=', now())
            ->whereNull('used_at')
            ->latest()
            ->first();

        if ($otpRecord) {
            $otpRecord->update(['used_at' => now()]);
            return true;
        }

        return false;
    }

    public static function destroyOTPs($user): void
    {
        Otp::where('user_type', get_class($user))
            ->where('user_id', $user->id)
            ->delete();

    }
}
