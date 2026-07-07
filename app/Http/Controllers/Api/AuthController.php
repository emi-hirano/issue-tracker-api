<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. 送られてきた email と password を検証
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. その email のユーザーを探す
        $user = User::where('email', $validated['email'])->first();

        // 3. ユーザーが居ない or パスワードが違う → 拒否
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => '認証に失敗しました'], 401);
        }

        // 4. 正しければトークンを発行して返す
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }
}