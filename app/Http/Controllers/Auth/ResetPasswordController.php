<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'The provided password was incorrect.',
            ], 422);
        }

        $user->update([
            'password' => $request->new_password,
        ]);

        return response()->json([
            'message' => 'Password has been reset.'
        ], 200);
    }
}
