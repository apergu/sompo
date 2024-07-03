<?php

use App\Http\Controllers\BlastingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {
    // Route::get('blasting', [BlastingController::class, 'index']);

    Route::prefix('blasting')->group(function() {
        Route::get('/', [BlastingController::class, 'index']);
    });

    Route::prefix('customer')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
    });

    Route::prefix('reports')->group(function() {
        Route::get('/', [ReportsController::class, 'index']);
        Route::get('/received', [ReportsController::class, 'received']);
    });
});
