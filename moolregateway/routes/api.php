<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MerchantController;
use App\Http\Middleware\ApikeyAuth;
use App\Http\Middleware\Cors;
use \App\Http\Controllers\Gateway\MomoController;
use \App\Http\Controllers\Gateway\CardController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/transactions', [TransactionController::class, 'index']);

// Route::middleware([ ApikeyAuth::class  ])->group(function(){
//     Route::post('/initiate-momo', [MerchantController::class, 'initiateMomo'])->middleware('cors');
//     Route::post('/proceed-momo', [MerchantController::class, 'proceedMomo'])->middleware('cors');
// });

Route::post('/momo/initialize', [MomoController::class, 'initialize'])->middleware(['gatewayAuth', 'decrypt']);
Route::post('/card/initialize', [CardController::class, 'initializeCard'])->middleware(['gatewayAuth', 'decrypt']);
Route::post('/momo/proceed', [MomoController::class, 'finalize'])->middleware(['gatewayAuth', 'decrypt']);