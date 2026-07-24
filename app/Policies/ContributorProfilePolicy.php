<?php

namespace App\Policies;

use App\Models\ContributorProfile;
use App\Models\User;

class ContributorProfilePolicy
{
    public function before(
        User $user,
        string $ability
    ): ?bool {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(
        User $user
    ): bool {
        return false;
    }

    public function view(
        User $user,
        ContributorProfile $profile
    ): bool {
        return false;
    }

    public function create(
        User $user
    ): bool {
        return false;
    }

    public function update(
        User $user,
        ContributorProfile $profile
    ): bool {
        return false;
    }

    public function delete(
        User $user,
        ContributorProfile $profile
    ): bool {
        return false;
    }

    public function deleteAny(
        User $user
    ): bool {
        return false;
    }
}
