<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    // ユーザー一覧（プルダウン用に id と name のみ返す）
    public function index()
    {
        return User::select('id', 'name')->get();
    }
}