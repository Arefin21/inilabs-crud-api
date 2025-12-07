<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::post('logout', [AuthController::class, 'logout']);

    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => $request->user(),
        ]);
    });
});
