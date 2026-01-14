<?php

use App\Models\CargoShipment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('qr/{uuid}', [App\Http\Controllers\CargoShipmentController::class, 'qr'])
//    ->can('qr', CargoShipment::class)
    ->name('cargo_shipments.qr');

Auth::routes(['register' => false]);

Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', function () {
            return view('dashboard');
        })->name('dashboard');

        require base_path('routes/cargo_shipments.php');

    });
