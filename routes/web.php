<?php

use App\Http\Controllers\CargoShipmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Models\CargoShipment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

if (app()->environment('local')) {
    Route::prefix('autologin')->group(function () {
        Route::get('/{email}', [UsersController::class, 'autologin'])
            ->name('users.autologin');
    });
}

Route::get('qr/{uuid}', [CargoShipmentController::class, 'qr'])
//    ->can('qr', CargoShipment::class)
    ->name('cargo_shipments.qr');

Auth::routes(['register' => false]);

Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        require base_path('routes/cargo_shipments.php');
        require base_path('routes/cargo_rate_requests.php');
        require base_path('routes/users.php');
        require base_path('routes/trips.php');
    });
