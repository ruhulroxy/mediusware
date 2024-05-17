<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;

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

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/users', [UserController::class, 'create'])->name('users');
Route::post('/users', [UserController::class, 'store'])->name('users');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/deposit', [TransactionController::class, 'showDeposits']);
Route::post('/deposit', [TransactionController::class, 'deposit']);
Route::get('/withdrawal', [TransactionController::class, 'showWithdrawals']);
Route::post('/withdrawal', [TransactionController::class, 'withdraw']);

Auth::routes();

