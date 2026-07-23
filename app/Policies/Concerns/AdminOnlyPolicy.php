<?php

namespace App\Policies\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait AdminOnlyPolicy
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
        Model $model
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
        Model $model
    ): bool {
        return $user->isHr();
    }

    public function delete(
        User $user,
        Model $model
    ): bool {
        return $user->isHr();
    }

    public function deleteAny(
        User $user
    ): bool {
        return $user->isHr();
    }

    public function restore(
        User $user,
        Model $model
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
        Model $model
    ): bool {
        return false;
    }

    public function forceDeleteAny(
        User $user
    ): bool {
        return false;
    }

    public function reorder(
        User $user
    ): bool {
        return $user->isHr();
    }

    public function replicate(
        User $user,
        Model $model
    ): bool {
        return $user->isHr();
    }
}
