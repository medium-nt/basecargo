<?php

use App\Models\CargoShipment;

Route::prefix('/cargo_shipments')->group(function () {
    Route::get('', [App\Http\Controllers\CargoShipmentController::class, 'index'])
        ->can('viewAny', CargoShipment::class)
        ->name('cargo_shipments.index');
});
