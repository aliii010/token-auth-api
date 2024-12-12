<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();

        $user = User::create($fields);

        $user->sendOTPCode();

        return response()->json([
            'user' => $user,
            'token' => $user->createToken($user->email)->plainTextToken,
        ], 201);
    }
}
