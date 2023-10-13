<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Gateway\MomoController;

Route::get('/', function () {

    return 'Admin!';

});

Route::post('/momo/initialize', [MomoController::class, 'initialize'])->middleware('gatewayAuth');