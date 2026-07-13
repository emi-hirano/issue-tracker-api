<?php

use Illuminate\Support\Facades\Route;

// このプロジェクトはAPI専用のため、ルートURLは疎通確認用のJSONのみ返す
Route::get('/', function () {
    return response()->json(['status' => 'ok']);
});