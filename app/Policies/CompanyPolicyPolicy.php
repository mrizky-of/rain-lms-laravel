<?php

namespace App\Policies;

use App\Models\CompanyPolicy;
use App\Models\User;

class CompanyPolicyPolicy
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
        return $user->isHr();
    }

    public function view(
        User $user,
        CompanyPolicy $model
    ): bool {
        return $user->isHr();
    }

    public function create(
        User $user
    ): bool {
        return $user->isHr();
    }

    public function update(
        User $user,
        CompanyPolicy $model
    ): bool {
        return $user->isHr();
    }

    public function delete(
        User $user,
        CompanyPolicy $model
    ): bool {
        return $user->isHr();
    }

    public function deleteAny(
        User $user
    ): bool {
        return $user->isHr();
    }
}
