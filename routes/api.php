<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;
Route::get('/user', function (Request $request) {return $request->user();})->middleware('auth:sanctum');

Route::get('/questionnaire/{id}/questions', [ApiController::class, 'getQuestions']);
Route::post('/questionnaire/{id}/submit', [ApiController::class, 'submitAnswers']);
