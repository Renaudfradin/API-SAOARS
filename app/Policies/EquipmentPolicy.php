<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EquipmentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Equipment $equipment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Equipment $equipment): bool
    {
        return true;
    }

    public function delete(User $user, Equipment $equipment): bool
    {
        return true;
    }

    public function restore(User $user, Equipment $equipment): bool
    {
        return true;
    }

    public function forceDelete(User $user, Equipment $equipment): bool
    {
        return true;
    }
}
