<?php

namespace App\Policies;

use App\Models\TrainingModule;
use App\Models\User;

class TrainingModulePolicy
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
        return $user->isContributor();
    }

    public function view(
        User $user,
        TrainingModule $module
    ): bool {
        return $this
            ->belongsToContributorDepartment(
                $user,
                $module
            );
    }

    public function create(
        User $user
    ): bool {
        return $user->isContributor();
    }

    public function update(
        User $user,
        TrainingModule $module
    ): bool {
        return $this
            ->belongsToContributorDepartment(
                $user,
                $module
            );
    }

    public function delete(
        User $user,
        TrainingModule $module
    ): bool {
        return $this
            ->belongsToContributorDepartment(
                $user,
                $module
            );
    }

    public function deleteAny(
        User $user
    ): bool {
        return false;
    }

    private function belongsToContributorDepartment(
        User $user,
        TrainingModule $module
    ): bool {
        if (
            ! $user->isContributor()
            || ! $user->department_id
        ) {
            return false;
        }

        return $module
            ->departments()
            ->whereKey(
                $user->department_id
            )
            ->exists();
    }
}
