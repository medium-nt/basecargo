<?php

namespace App\Policies;

use App\Models\CargoRateRequest;
use App\Models\User;

class CargoRateRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CargoRateRequest $cargoRateRequest): bool
    {
        if ($user->isClient() || $user->isAgent()) {
            return $user->id === $cargoRateRequest->client_id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $user->isClient() || $user->isAgent();
    }

    public function update(User $user, CargoRateRequest $cargoRateRequest): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if (($user->isClient() || $user->isAgent()) && $user->id === $cargoRateRequest->client_id && $cargoRateRequest->isPending()) {
            return true;
        }

        return false;
    }

    public function delete(User $user, CargoRateRequest $cargoRateRequest): bool
    {
        // Админ всегда может удалять
        if ($user->isAdmin()) {
            return true;
        }

        // Клиент может удалять свои заявки в статусе pending
        if (($user->isClient() || $user->isAgent()) && $user->id === $cargoRateRequest->client_id && $cargoRateRequest->isPending()) {
            return true;
        }

        return false;
    }

    public function approve(User $user, CargoRateRequest $cargoRateRequest): bool
    {
        return $user->id === $cargoRateRequest->client_id && $cargoRateRequest->isAwaitingApproval();
    }

    public function reject(User $user, CargoRateRequest $cargoRateRequest): bool
    {
        return $user->id === $cargoRateRequest->client_id && $cargoRateRequest->isAwaitingApproval();
    }

    public function deleteFile(User $user, CargoRateRequest $cargoRateRequest): bool
    {
        // Админ всегда может удалять
        if ($user->isAdmin()) {
            return true;
        }

        // Клиент может удалять файлы только в своих заявках в статусе pending
        if (($user->isClient() || $user->isAgent()) && $user->id === $cargoRateRequest->client_id && $cargoRateRequest->isPending()) {
            return true;
        }

        return false;
    }

    public function restore(User $user, CargoRateRequest $cargoRateRequest): bool
    {
        return false;
    }

    public function forceDelete(User $user, CargoRateRequest $cargoRateRequest): bool
    {
        return false;
    }
}
