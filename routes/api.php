<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\AnswersController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Signup
Route::post('auth/signup', [ApiTokenController::class, 'signup']);

//Login
Route::post('auth/login', [ApiTokenController::class, 'login']);

//Destroy User
Route::middleware('auth:sanctum')->post('auth/destroy', [ApiTokenController::class, 'destroy']);

//Create New Question
Route::middleware('auth:sanctum')->post('questions/create', [QuestionsController::class, 'create']);

//Delete Question
Route::middleware('auth:sanctum')->delete('questions/{id}', [QuestionsController::class, 'delete']);

//Show All Questions
Route::middleware('auth:sanctum')->get('questions', [QuestionsController::class, 'showAll']);

//Vote for Answers
Route::middleware('auth:sanctum')->post('questions/{question}/answers/{answer}', [AnswersController::class, 'vote']);