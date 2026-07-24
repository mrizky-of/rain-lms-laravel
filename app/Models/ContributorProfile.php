<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ContributorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nickname',
        'bio',
        'expertise',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    /*
     * Pivot legacy tetap boleh dipakai untuk
     * menampilkan modul yang secara eksplisit
     * dikaitkan dengan contributor.
     */

    public function trainingModules(): BelongsToMany
    {
        return $this->belongsToMany(
            TrainingModule::class,
            'contributor_training_module'
        )->withTimestamps();
    }

    public function contributionCount(): int
    {
        $user = $this->user;

        if (! $user) {
            return 0;
        }

        return
            $user
                ->createdTrainingModules()
                ->count()
            +
            $user
                ->createdQuizzes()
                ->count()
            +
            $user
                ->createdFunTests()
                ->count();
    }

    public function computedRating(): int
    {
        $count =
            $this->contributionCount();

        return match (true) {
            $count === 0 => 0,

            $count <= 2 => 60,

            $count <= 5 => 75,

            $count <= 9 => 85,

            $count <= 14 => 95,

            default => 100,
        };
    }
}
