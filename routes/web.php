<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LoginController::class, 'index'])->name('login.index');
Route::post('/check-login', [LoginController::class, 'actionLogin'])->name('action.login');
Route::get('/register', [LoginController::class, 'register'])->name('register.index');
Route::post('/save-register', [LoginController::class, 'saveRegister'])->name('add.register');

Route::group([
    'middleware' => 'auth'
], function() {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
});
