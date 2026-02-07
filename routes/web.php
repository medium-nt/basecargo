<?php

use App\Models\CargoShipment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

if (App::environment(['local'])) {
    Route::prefix('autologin')->group(function () {
        Route::get('/{email}', [App\Http\Controllers\UsersController::class, 'autologin'])
            ->name('users.autologin');
    });
}

Route::get('qr/{uuid}', [App\Http\Controllers\CargoShipmentController::class, 'qr'])
//    ->can('qr', CargoShipment::class)
    ->name('cargo_shipments.qr');

Auth::routes(['register' => false]);

Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])
            ->name('dashboard');

        require base_path('routes/cargo_shipments.php');
        require base_path('routes/cargo_rate_requests.php');
        require base_path('routes/users.php');
        require base_path('routes/trips.php');

    });
