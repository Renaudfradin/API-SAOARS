<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AdminOnlyMutations;

class UserPolicy
{
    use AdminOnlyMutations;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
