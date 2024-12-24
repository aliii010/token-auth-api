<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\SendResetPasswordLink;
use App\Http\Requests\ResetPasswordVerifyRequest;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                "message" => "If your email exists in our system, you will receive a password reset link."
            ]);
        }

        $token = Str::random(60);
        $resetToken = PasswordResetToken::where('email', $user->email)->first();
        if (!$resetToken) {
            $resetToken = PasswordResetToken::create([
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]);
        }

        $user->notify(new SendResetPasswordLink($token));

        return response()->json([
            "message" => "If your email exists in our system, you will receive a password reset link."
        ]);
    }

    public function verify(ResetPasswordVerifyRequest $request)
    {
        $passwordReset = PasswordResetToken::where('email', $request->email)->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return response()->json(['message' => 'Invalid token.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => $request->new_password
        ]);

        $passwordReset->delete();

        return response()->json([
            'message' => 'Password has been reset.'
        ], 200);
    }
}
