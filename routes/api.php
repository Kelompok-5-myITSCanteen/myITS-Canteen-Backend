<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CanteenController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionDetailController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TableReservationController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\VendorController;

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('/admin', function (Request $request) {
        return response()->json(['message' => 'Welcome Admin!']);
    });
});

Route::middleware('auth:sanctum', 'role:user')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json(['message' => 'Welcome User!']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthenticatedSessionController::class, 'me']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::resource('transactions', TransactionController::class);
});

Route::resource('canteens', CanteenController::class);
Route::resource('vendors', VendorController::class);

Route::get('canteens/{id}/vendors', [CanteenController::class, 'getVendors']);