<?php

use App\Http\Controllers\AuthController;
//use App\Http\Middleware\JWTMiddleware;
use App\Http\Middleware\AuthenticateWebhookMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
  Route::post('login', [AuthController::class, 'login']);
  Route::middleware('api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('payload', [AuthController::class, 'payload']);
  });
});

Route::post('webhooks/test', function () {
  return response()->json(['success' => true]);
})->name('test')->middleware(AuthenticateWebhookMiddleware::class);
