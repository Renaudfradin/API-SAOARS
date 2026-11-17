<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait AdminOnlyMutations
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, mixed $record = null): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, mixed $record = null): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, mixed $record = null): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, mixed $record = null): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function restoreAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
