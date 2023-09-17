<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\SurveyFormController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->middleware('api.response')->group(function () {

    Route::post('register', [AuthController::class, 'store']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::apiResource('user', UserController::class)->only('index');
        Route::apiResource('survey', SurveyFormController::class)->only(['index', 'store', 'show']);

        Route::post('logout', [AuthController::class, 'logout']);
    });
});
