<?php

namespace App\Policies;

use App\Models\HomeCharacter;
use App\Models\User;
use App\Policies\Concerns\AdminOnlyMutations;

class HomeCharacterPolicy
{
    use AdminOnlyMutations;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, HomeCharacter $homeCharacter): bool
    {
        return true;
    }
}
