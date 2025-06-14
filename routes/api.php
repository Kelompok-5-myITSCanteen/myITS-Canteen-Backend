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
use App\Http\Controllers\ChairTableViewController;

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('/admin', function (Request $request) {
        return response()->json(['message' => 'Welcome Admin!']);
    });
    Route::get('/transactions/vendor', [TransactionController::class, 'getByVendor']);
    Route::resource('menus', MenuController::class);
    Route::post('menus/update/{menu}', [MenuController::class, 'updateMenu']);
    Route::get('/vendors/daily-data', [VendorController::class, 'getDailyData']);

    Route::get('/sales-last-week', [VendorController::class, 'salesLastWeek']);
    Route::get('/top-menu-last-week', [VendorController::class, 'topMenuLastWeek']);
    Route::get('/sales-report', [VendorController::class, 'salesReport']);

    Route::post('/accept-transaction/{transaction}', [TransactionController::class, 'acceptTransaction']);
    Route::post('/reject-transaction/{transaction}', [TransactionController::class, 'rejectTransaction']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthenticatedSessionController::class, 'me']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::get('/transactions/user', [TransactionController::class, 'getByUser']);
    Route::resource('transactions', TransactionController::class);
});

// by canteen
Route::get('/canteens', [CanteenController::class, 'index']);
Route::prefix('canteens/{canteen}')->group(function () {
    Route::get('/menus', [MenuController::class, 'showMenuByCanteen']);
    Route::get('/vendors', [CanteenController::class, 'getVendors']);
    Route::get('/available-chairs', [ChairTableViewController::class, 'getAvailableChairs']);
});

Route::get('/vendors/menus', [MenuController::class, 'showMenuByVendor']);
