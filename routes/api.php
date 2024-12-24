<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});



 // Routes for Admin Role
 Route::middleware(['auth:sanctum', 'role_check:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Welcome to Admin Dashboard']);
    });
});

// Routes for Vendor Role
Route::middleware(['auth:sanctum', 'role_check:vendor'])->group(function () {
    Route::get('/vendor/dashboard', function () {
        return response()->json(['message' => 'Welcome to Vendor Dashboard']);
    });
});

// Routes for Customer Role
Route::middleware(['auth:sanctum', 'role_check:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return response()->json(['message' => 'Welcome to Customer Dashboard']);
    });
});
