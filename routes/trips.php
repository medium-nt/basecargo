<?php

use App\Http\Controllers\TripController;
use App\Models\Trip;

Route::prefix('/trips')->group(function () {
    Route::get('/domestic', [TripController::class, 'index'])
        ->defaults('type', 'domestic')
        ->can('viewAny', Trip::class)
        ->name('trips.domestic');

    Route::get('/international', [TripController::class, 'index'])
        ->defaults('type', 'international')
        ->can('viewAny', Trip::class)
        ->name('trips.international');

    Route::get('/create', [TripController::class, 'create'])
        ->can('create', Trip::class)
        ->name('trips.create');

    Route::post('', [TripController::class, 'store'])
        ->can('create', Trip::class)
        ->name('trips.store');

    Route::get('/{trip}', [TripController::class, 'show'])
        ->can('view', 'trip')
        ->name('trips.show');

    Route::get('/{trip}/edit', [TripController::class, 'edit'])
        ->can('update', 'trip')
        ->name('trips.edit');

    Route::put('/{trip}', [TripController::class, 'update'])
        ->can('update', 'trip')
        ->name('trips.update');

    Route::delete('/{trip}', [TripController::class, 'destroy'])
        ->can('delete', 'trip')
        ->name('trips.destroy');

    Route::delete('/{trip}/cargo/{cargoShipment}', [TripController::class, 'detachCargo'])
        ->can('detachCargo', 'trip')
        ->name('trips.detach_cargo');
});
