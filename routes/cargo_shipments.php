<?php

use App\Models\CargoShipment;

Route::prefix('/cargo_shipments')->group(function () {
    Route::get('', [App\Http\Controllers\CargoShipmentController::class, 'index'])
        ->can('viewAny', CargoShipment::class)
        ->name('cargo_shipments.index');

    Route::get('/create', [App\Http\Controllers\CargoShipmentController::class, 'create'])
        ->can('create', CargoShipment::class)
        ->name('cargo_shipments.create');

    Route::post('', [App\Http\Controllers\CargoShipmentController::class, 'store'])
        ->can('create', CargoShipment::class)
        ->name('cargo_shipments.store');

    Route::get('/{cargoShipment}', [App\Http\Controllers\CargoShipmentController::class, 'show'])
        ->can('view', 'cargoShipment')
        ->name('cargo_shipments.show');

    Route::get('show_qr/{cargoShipment}', [App\Http\Controllers\CargoShipmentController::class, 'showQr'])
        ->can('view', 'cargoShipment')
        ->name('cargo_shipments.show_qr');

//    Route::get('edit/{uuid}', [App\Http\Controllers\CargoShipmentController::class, 'edit'])
//        ->can('update', 'cargoShipment')
//        ->name('cargo_shipments.edit');

    Route::get('edit/{cargoShipment}', [App\Http\Controllers\CargoShipmentController::class, 'edit'])
        ->can('update', 'cargoShipment')
        ->name('cargo_shipments.edit');

    Route::put('/{cargoShipment}', [App\Http\Controllers\CargoShipmentController::class, 'update'])
        ->can('update', 'cargoShipment')
        ->name('cargo_shipments.update');

    Route::delete('/{cargoShipment}', [App\Http\Controllers\CargoShipmentController::class, 'destroy'])
        ->can('delete', 'cargoShipment')
        ->name('cargo_shipments.destroy');

    Route::delete('/{cargoShipment}/files/{fileId}', [App\Http\Controllers\CargoShipmentController::class, 'destroyFile'])
        ->can('deleteFile', 'cargoShipment')
        ->name('cargo_shipments.files.destroy');
});
