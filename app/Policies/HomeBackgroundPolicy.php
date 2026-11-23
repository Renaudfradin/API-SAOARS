<?php

namespace App\Policies;

use App\Models\HomeBackground;
use App\Models\User;
use App\Policies\Concerns\AdminOnlyMutations;

class HomeBackgroundPolicy
{
    use AdminOnlyMutations;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, HomeBackground $homeBackground): bool
    {
        return true;
    }
}
