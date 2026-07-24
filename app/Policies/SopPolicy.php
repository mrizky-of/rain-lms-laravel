<?php

namespace App\Policies;

use App\Models\Sop;
use App\Models\User;

class SopPolicy
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
        Sop $model
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
        Sop $model
    ): bool {
        return $user->isHr();
    }

    public function delete(
        User $user,
        Sop $model
    ): bool {
        return $user->isHr();
    }

    public function deleteAny(
        User $user
    ): bool {
        return $user->isHr();
    }
}
