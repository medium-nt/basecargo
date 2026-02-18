<?php

use App\Http\Controllers\CargoShipmentController;
use App\Http\Controllers\CargoShipmentImportController;
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

    Route::get('/export-all', [CargoShipmentController::class, 'exportAll'])
        ->can('exportAll', CargoShipment::class)
        ->name('cargo_shipments.export_all');

    // Импорт из Excel - ДОЛЖНО БЫТЬ ПЕРЕД /{cargoShipment}!
    Route::prefix('/import')->group(function () {
        Route::get('', [CargoShipmentImportController::class, 'create'])
            ->can('import', CargoShipment::class)
            ->middleware('throttle:10,1')
            ->name('cargo_shipments.import.create');

        Route::post('/upload', [CargoShipmentImportController::class, 'upload'])
            ->can('import', CargoShipment::class)
            ->middleware('throttle:5,1')
            ->name('cargo_shipments.import.upload');

        Route::get('/mapping', [CargoShipmentImportController::class, 'mapping'])
            ->can('import', CargoShipment::class)
            ->name('cargo_shipments.import.mapping');

        Route::post('/validate', [CargoShipmentImportController::class, 'validateMapping'])
            ->can('import', CargoShipment::class)
            ->middleware('throttle:10,1')
            ->name('cargo_shipments.import.validate');

        Route::get('/preview', [CargoShipmentImportController::class, 'preview'])
            ->can('import', CargoShipment::class)
            ->name('cargo_shipments.import.preview');

        Route::post('/store', [CargoShipmentImportController::class, 'store'])
            ->can('import', CargoShipment::class)
            ->middleware('throttle:5,1')
            ->name('cargo_shipments.import.store');

        Route::get('/cancel', [CargoShipmentImportController::class, 'cancel'])
            ->can('import', CargoShipment::class)
            ->name('cargo_shipments.import.cancel');
    });

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

    Route::post('/export-selected', [CargoShipmentController::class, 'exportSelected'])
        ->can('exportSelected', CargoShipment::class)
        ->name('cargo_shipments.export_selected');
});
