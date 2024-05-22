<?php

use App\Http\Controllers\StripController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/pay', [StripController::class, 'pay']);
