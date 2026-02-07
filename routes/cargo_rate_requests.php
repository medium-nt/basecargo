<?php

use App\Http\Controllers\CargoRateRequestController;
use App\Models\CargoRateRequest;

Route::prefix('/cargo_rate_requests')->group(function () {
    Route::get('', [CargoRateRequestController::class, 'index'])
        ->can('viewAny', CargoRateRequest::class)
        ->name('cargo_rate_requests.index');

    Route::post('', [CargoRateRequestController::class, 'store'])
        ->can('create', CargoRateRequest::class)
        ->name('cargo_rate_requests.store');

    Route::get('/{cargoRateRequest}', [CargoRateRequestController::class, 'show'])
        ->can('view', 'cargoRateRequest')
        ->name('cargo_rate_requests.show');

    Route::get('/{cargoRateRequest}/edit', [CargoRateRequestController::class, 'edit'])
        ->can('update', 'cargoRateRequest')
        ->name('cargo_rate_requests.edit');

    Route::put('/{cargoRateRequest}', [CargoRateRequestController::class, 'update'])
        ->can('update', 'cargoRateRequest')
        ->name('cargo_rate_requests.update');

    Route::post('/{cargoRateRequest}/approve', [CargoRateRequestController::class, 'approve'])
        ->can('approve', 'cargoRateRequest')
        ->name('cargo_rate_requests.approve');

    Route::post('/{cargoRateRequest}/reject', [CargoRateRequestController::class, 'reject'])
        ->can('reject', 'cargoRateRequest')
        ->name('cargo_rate_requests.reject');

    Route::delete('/{cargoRateRequest}/files/{fileId}', [CargoRateRequestController::class, 'destroyFile'])
        ->can('deleteFile', 'cargoRateRequest')
        ->name('cargo_rate_requests.files.destroy');

    Route::delete('/{cargoRateRequest}', [CargoRateRequestController::class, 'destroy'])
        ->can('delete', 'cargoRateRequest')
        ->name('cargo_rate_requests.destroy');
});
