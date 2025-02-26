<?php

namespace App\Policies;

use App\Models\Character;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CharacterPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Character $character): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Character $character): bool
    {
        return true;
    }

    public function delete(User $user, Character $character): bool
    {
        return true;
    }

    public function restore(User $user, Character $character): bool
    {
        return true;
    }

    public function forceDelete(User $user, Character $character): bool
    {
        return true;
    }
}
