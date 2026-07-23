<?php

namespace App\Policies;

use App\Models\ManagerReview;
use App\Models\User;

class ManagerReviewPolicy
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
        return $user->isManager()
            || $user->isHr();
    }

    public function view(
        User $user,
        ManagerReview $review
    ): bool {
        if (
            $user->isHr()
        ) {
            return true;
        }

        return $user->isManager()
            && (int) $review->manager_id
                === (int) $user->id;
    }

    /*
     * Manager melakukan review di frontend /manager-review.
     */
    public function create(
        User $user
    ): bool {
        return $user->isHr();
    }

    public function update(
        User $user,
        ManagerReview $review
    ): bool {
        return $user->isHr();
    }

    public function delete(
        User $user,
        ManagerReview $review
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
        ManagerReview $review
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
        ManagerReview $review
    ): bool {
        return false;
    }

    public function forceDeleteAny(
        User $user
    ): bool {
        return false;
    }
}
