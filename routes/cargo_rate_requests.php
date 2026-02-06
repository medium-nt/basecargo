<?php

use App\Models\CargoRateRequest;

Route::prefix('/cargo_rate_requests')->group(function () {
    Route::get('', [App\Http\Controllers\CargoRateRequestController::class, 'index'])
        ->can('viewAny', CargoRateRequest::class)
        ->name('cargo_rate_requests.index');

    Route::post('', [App\Http\Controllers\CargoRateRequestController::class, 'store'])
        ->can('create', CargoRateRequest::class)
        ->name('cargo_rate_requests.store');

    Route::get('/{cargoRateRequest}', [App\Http\Controllers\CargoRateRequestController::class, 'show'])
        ->can('view', 'cargoRateRequest')
        ->name('cargo_rate_requests.show');

    Route::get('/{cargoRateRequest}/edit', [App\Http\Controllers\CargoRateRequestController::class, 'edit'])
        ->can('update', 'cargoRateRequest')
        ->name('cargo_rate_requests.edit');

    Route::put('/{cargoRateRequest}', [App\Http\Controllers\CargoRateRequestController::class, 'update'])
        ->can('update', 'cargoRateRequest')
        ->name('cargo_rate_requests.update');

    Route::post('/{cargoRateRequest}/approve', [App\Http\Controllers\CargoRateRequestController::class, 'approve'])
        ->can('approve', 'cargoRateRequest')
        ->name('cargo_rate_requests.approve');

    Route::post('/{cargoRateRequest}/reject', [App\Http\Controllers\CargoRateRequestController::class, 'reject'])
        ->can('reject', 'cargoRateRequest')
        ->name('cargo_rate_requests.reject');

    Route::delete('/{cargoRateRequest}/files/{fileId}', [App\Http\Controllers\CargoRateRequestController::class, 'destroyFile'])
        ->can('deleteFile', 'cargoRateRequest')
        ->name('cargo_rate_requests.files.destroy');

    Route::delete('/{cargoRateRequest}', [App\Http\Controllers\CargoRateRequestController::class, 'destroy'])
        ->can('delete', 'cargoRateRequest')
        ->name('cargo_rate_requests.destroy');
});
