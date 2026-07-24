<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
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
        Department $department
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
        Department $department
    ): bool {
        return false;
    }

    public function delete(
        User $user,
        Department $department
    ): bool {
        return false;
    }

    public function deleteAny(
        User $user
    ): bool {
        return false;
    }
}
