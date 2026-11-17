<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;
use App\Policies\Concerns\AdminOnlyMutations;

class EquipmentPolicy
{
    use AdminOnlyMutations;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Equipment $equipment): bool
    {
        return true;
    }
}
