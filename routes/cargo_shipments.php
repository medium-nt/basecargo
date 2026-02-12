<?php

use App\Http\Controllers\CargoShipmentController;
use App\Models\CargoShipment;

Route::prefix('/cargo_shipments')->group(function () {
    Route::get('', [CargoShipmentController::class, 'index'])
        ->can('viewAny', CargoShipment::class)
        ->name('cargo_shipments.index');

    Route::get('/create', [CargoShipmentController::class, 'create'])
        ->can('create', CargoShipment::class)
        ->name('cargo_shipments.create');

    Route::post('', [CargoShipmentController::class, 'store'])
        ->can('create', CargoShipment::class)
        ->name('cargo_shipments.store');

    Route::get('/download-template', [CargoShipmentController::class, 'downloadTemplate'])
        ->can('create', CargoShipment::class)
        ->name('cargo_shipments.download_template');

    Route::get('/{cargoShipment}', [CargoShipmentController::class, 'show'])
        ->can('view', 'cargoShipment')
        ->name('cargo_shipments.show');

    Route::get('show_qr/{cargoShipment}', [CargoShipmentController::class, 'showQr'])
        ->can('view', 'cargoShipment')
        ->name('cargo_shipments.show_qr');

    Route::get('edit/{cargoShipment}', [CargoShipmentController::class, 'edit'])
        ->can('update', 'cargoShipment')
        ->name('cargo_shipments.edit');

    Route::put('/{cargoShipment}', [CargoShipmentController::class, 'update'])
        ->can('update', 'cargoShipment')
        ->name('cargo_shipments.update');

    Route::delete('/{cargoShipment}', [CargoShipmentController::class, 'destroy'])
        ->can('delete', 'cargoShipment')
        ->name('cargo_shipments.destroy');

    Route::delete('/{cargoShipment}/files/{fileId}', [CargoShipmentController::class, 'destroyFile'])
        ->can('deleteFile', 'cargoShipment')
        ->name('cargo_shipments.files.destroy');

    Route::delete('/{cargoShipment}/photo', [CargoShipmentController::class, 'destroyPhoto'])
        ->can('update', 'cargoShipment')
        ->name('cargo_shipments.photo.destroy');

    Route::post('/attach-to-trip', [CargoShipmentController::class, 'attachToTrip'])
        ->can('attachToTrip', CargoShipment::class)
        ->name('cargo_shipments.attach_to_trip');
});
