<?php

namespace App\Policies;

use App\Models\Imagination;
use App\Models\User;
use App\Policies\Concerns\AdminOnlyMutations;

class ImaginationPolicy
{
    use AdminOnlyMutations;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Imagination $imagination): bool
    {
        return true;
    }
}
