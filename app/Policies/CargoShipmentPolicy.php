<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\CargoShipment;
use App\Models\User;

class CargoShipmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CargoShipment $cargoShipment): bool
    {
        return false;
    }

    /**
     * Проверка, что можно показывать детали груза
     */
    public function qr(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CargoShipment $cargoShipment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CargoShipment $cargoShipment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CargoShipment $cargoShipment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CargoShipment $cargoShipment): bool
    {
        return false;
    }
}
