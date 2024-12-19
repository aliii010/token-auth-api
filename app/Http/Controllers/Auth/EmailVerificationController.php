<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerifyRequest;

class EmailVerificationController extends Controller
{
    public function verify(EmailVerifyRequest $request)
    {
        $user = $request->user();

        if ($user->otp != $request->otp) {
            return response()->json([
                'message' => 'Invalid OTP.'
            ], 422);
        }

        $user->email_verified_at = now();
        $user->email_verified = true;
        $user->otp = null;
        $user->save();
        return response()->json([
            'message' => 'Email has been verified.'
        ], 200);
    }
}
