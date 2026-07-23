<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function before(
        User $user,
        string $ability
    ): ?bool {
        if (
            $user->isSuperAdmin()
        ) {
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
        User $model
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
        User $model
    ): bool {
        return $user->isHr()
            && ! $model->isSuperAdmin();
    }

    public function delete(
        User $user,
        User $model
    ): bool {
        return $user->isHr()
            && $user->id !== $model->id
            && ! $model->isSuperAdmin();
    }

    public function deleteAny(
        User $user
    ): bool {
        return false;
    }

    public function restore(
        User $user,
        User $model
    ): bool {
        return false;
    }

    public function restoreAny(
        User $user
    ): bool {
        return false;
    }

    public function forceDelete(
        User $user,
        User $model
    ): bool {
        return false;
    }

    public function forceDeleteAny(
        User $user
    ): bool {
        return false;
    }
}
