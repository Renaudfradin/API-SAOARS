<?php

namespace App\Policies;

use App\Models\Character;
use App\Models\User;
use App\Policies\Concerns\AdminOnlyMutations;

class CharacterPolicy
{
    use AdminOnlyMutations;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Character $character): bool
    {
        return true;
    }
}
