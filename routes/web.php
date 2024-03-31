<?php

use Illuminate\Support\Facades\Route;

Route::resource('/products', \App\Http\Controllers\ProductController::class);

Route::resource('/', \App\Http\Controllers\HomeController::class);
