<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ApiTokenController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Signup
Route::post('auth/signup', [ApiTokenController::class, 'signup']);