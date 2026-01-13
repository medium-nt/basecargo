<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', function () {
            return view('dashboard');
        })->name('dashboard');

        require base_path('routes/cargo_shipments.php');

    });
