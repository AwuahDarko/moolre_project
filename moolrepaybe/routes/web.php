<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthLoginRegisterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthLoginRegisterController::class, 'login'])->name('login');
Route::post('/authenticate', [AuthLoginRegisterController::class, 'authenticate'])->name('authenticate');
Route::get('/register', [AuthLoginRegisterController::class, 'register'])->name('register');
Route::get('/dashboard', [AuthLoginRegisterController::class, 'dashboard'])->name('dashboard');
Route::post('/store', [AuthLoginRegisterController::class, 'store'])->name('store');
Route::post('/settings', [AuthLoginRegisterController::class, 'settings'])->name('settings');
Route::get('/logout', [AuthLoginRegisterController::class, 'logout'])->name('logout');
Route::get('/key', [AuthLoginRegisterController::class, 'generate_api_key'])->name('generate.key');
Route::get('/transactions', [AuthLoginRegisterController::class, 'transactions'])->name('transactions');