<?php

namespace App\Policies;

use App\Models\Imagination;
use App\Models\User;

class ImaginationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Imagination $imagination): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Imagination $imagination): bool
    {
        return true;
    }

    public function delete(User $user, Imagination $imagination): bool
    {
        return true;
    }

    public function restore(User $user, Imagination $imagination): bool
    {
        return true;
    }

    public function forceDelete(User $user, Imagination $imagination): bool
    {
        return true;
    }
}
