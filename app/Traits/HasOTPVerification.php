<?php

namespace App\Traits;

use App\Notifications\SendOTPVerification;

trait HasOTPVerification
{
    public function sendOTPCode()
    {
        if ($this->email_verified) {
            return response()->json([
                'message' => 'Email already verified'
            ], 400);
        }
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        $this->notify(new SendOTPVerification($otp));

        return $otp;
    }
}
