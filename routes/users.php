<?php

use App\Http\Controllers\UsersController;
use App\Models\User;

Route::prefix('/users')->group(function () {
    Route::get('', [UsersController::class, 'index'])
        ->can('viewAny', User::class)
        ->name('users.index');

    Route::get('/create', [UsersController::class, 'create'])
        ->can('create', User::class)
        ->name('users.create');

    Route::post('', [UsersController::class, 'store'])
        ->can('create', User::class)
        ->name('users.store');

    Route::get('/{user}/edit', [UsersController::class, 'edit'])
        ->can('update', 'user')
        ->name('users.edit');

    Route::put('/{user}', [UsersController::class, 'update'])
        ->can('update', 'user')
        ->name('users.update');

    Route::delete('/{user}', [UsersController::class, 'destroy'])
        ->can('delete', 'user')
        ->name('users.destroy');
});
