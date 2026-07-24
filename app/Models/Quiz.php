<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'training_module_id',
        'created_by',
        'title',
        'question_count',
        'time_limit_minutes',
        'max_attempts',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'question_count' => 'integer',

            'time_limit_minutes' => 'integer',

            'max_attempts' => 'integer',

            'is_active' => 'boolean',
        ];
    }

    public function trainingModule(): BelongsTo
    {
        return $this->belongsTo(
            TrainingModule::class
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function questions(): HasMany
    {
        return $this->hasMany(
            Question::class
        )->orderBy('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(
            QuizAttempt::class
        );
    }

    public function scopeManageableBy(
        Builder $query,
        User $user
    ): Builder {
        if (
            $user->isSuperAdmin()
        ) {
            return $query;
        }

        if (
            $user->isContributor()
        ) {
            return $query
                ->whereHas(
                    'trainingModule',
                    fn (
                        Builder $moduleQuery
                    ) => $moduleQuery
                        ->manageableBy(
                            $user
                        )
                );
        }

        return $query->whereRaw(
            '1 = 0'
        );
    }

    public function isQuestionComplete(): bool
    {
        return
            $this->questions()->count()
            ===
            $this->question_count;
    }
}
