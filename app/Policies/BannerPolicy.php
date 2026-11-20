<?php

namespace App\Policies;

use App\Models\Banner;
use App\Models\User;
use App\Policies\Concerns\AdminOnlyMutations;

class BannerPolicy
{
    use AdminOnlyMutations;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Banner $banner): bool
    {
        return true;
    }
}
