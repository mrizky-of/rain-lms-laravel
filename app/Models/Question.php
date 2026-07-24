<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    public const TYPE_MULTIPLE_CHOICE =
        'multiple_choice';

    public const TYPE_ESSAY =
        'essay';

    protected $fillable = [
        'quiz_id',
        'type',
        'question_text',
        'options',
        'correct_answer',
        'essay_answer_key',
        'explanation',
        'points',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',

            'points' => 'decimal:2',

            'sort_order' => 'integer',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(
            Quiz::class
        );
    }

    public function answers(): HasMany
    {
        return $this->hasMany(
            QuizAnswer::class
        );
    }
}