<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
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
        Quiz $quiz
    ): bool {
        return $this
            ->canManageQuiz(
                $user,
                $quiz
            );
    }

    public function create(
        User $user
    ): bool {
        return $user->isContributor();
    }

    public function update(
        User $user,
        Quiz $quiz
    ): bool {
        return $this
            ->canManageQuiz(
                $user,
                $quiz
            );
    }

    public function delete(
        User $user,
        Quiz $quiz
    ): bool {
        return $this
            ->canManageQuiz(
                $user,
                $quiz
            );
    }

    public function deleteAny(
        User $user
    ): bool {
        return false;
    }

    private function canManageQuiz(
        User $user,
        Quiz $quiz
    ): bool {
        if (
            ! $user->isContributor()
        ) {
            return false;
        }

        return $quiz
            ->trainingModule
            ?->departments()
            ->whereKey(
                $user->department_id
            )
            ->exists()
            ?? false;
    }
}
