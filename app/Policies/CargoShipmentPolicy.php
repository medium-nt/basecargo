<?php

namespace App\Policies;

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
        if ($user->isAgent()) {
            return $user->id === $cargoShipment->responsible_user_id || $user->id === $cargoShipment->client_id;
        }

        if ($user->isClient()) {
            return $user->id === $cargoShipment->client_id;
        }

        return true;
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
        return $user->isAdmin() || $user->isAgent() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CargoShipment $cargoShipment): bool
    {
        return $user->isAdmin() || $user->isManager() || ($user->isAgent() && $user->id === $cargoShipment->client_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CargoShipment $cargoShipment): bool
    {
        return $user->isAdmin();
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

    /**
     * Загрузка файлов к грузу
     */
    public function uploadFiles(User $user, CargoShipment $cargoShipment): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if ($user->isAgent()) {
            return $user->id === $cargoShipment->responsible_user_id;
        }

        return false;
    }

    /**
     * Удаление файлов из груза
     */
    public function deleteFile(User $user, CargoShipment $cargoShipment): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Прикрепление груза к рейсу
     */
    public function attachToTrip(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Экспорт выбранных грузов
     */
    public function exportSelected(User $user): bool
    {
        return $user->isAdmin() || $user->isManager() || $user->isAgent() || $user->isClient();
    }

    /**
     * Экспорт всех грузов
     */
    public function exportAll(User $user): bool
    {
        return $user->isAdmin() || $user->isManager() || $user->isAgent() || $user->isClient();
    }
}
