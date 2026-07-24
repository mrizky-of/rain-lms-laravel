<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FunTestQuestion extends Model
{
    public const MODE_DRAG_DROP =
        'drag_drop';

    public const MODE_MATCH_IT =
        'match_it';

    public const MODE_WORD_SCRAMBLE =
        'word_scramble';

    public const MODE_SPEED_QUIZ =
        'speed_quiz';

    public const MODE_TRUE_FALSE =
        'true_false';

    public const MODE_FILL_BLANK =
        'fill_blank';

    protected $fillable = [
        'department_id',
        'created_by',
        'mode',
        'title',
        'prompt',
        'payload',
        'answer',
        'explanation',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',

            'answer' => 'array',

            'is_active' => 'boolean',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
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
            && $user->department_id
        ) {
            return $query->where(
                'department_id',
                $user->department_id
            );
        }

        return $query->whereRaw(
            '1 = 0'
        );
    }
}
