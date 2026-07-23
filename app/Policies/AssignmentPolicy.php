<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
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
        return $user->isHr()
            || $user->isManager();
    }

    public function view(
        User $user,
        Assignment $assignment
    ): bool {
        if (
            $user->isHr()
        ) {
            return true;
        }

        return $user->isManager()
            && (int)
                $assignment->manager_id
                === (int) $user->id;
    }

    /*
     * Manager membuat assignment melalui halaman
     * frontend /assignments, bukan CRUD Filament.
     */
    public function create(
        User $user
    ): bool {
        return $user->isHr();
    }

    public function update(
        User $user,
        Assignment $assignment
    ): bool {
        return $user->isHr();
    }

    public function delete(
        User $user,
        Assignment $assignment
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
        Assignment $assignment
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
        Assignment $assignment
    ): bool {
        return false;
    }

    public function forceDeleteAny(
        User $user
    ): bool {
        return false;
    }
}
