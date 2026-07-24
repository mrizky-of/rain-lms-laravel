<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $fillable = [

        'manager_id',

        'user_id',

        'training_module_id',

        'notes',

        'status',

        'due_at',

        'completed_at',

    ];

    protected function casts(): array
    {
        return [

            'due_at' => 'datetime',

            'completed_at' => 'datetime',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Manager
    |--------------------------------------------------------------------------
    */

    public function manager(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'manager_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Training Module
    |--------------------------------------------------------------------------
    */

    public function trainingModule(): BelongsTo
    {
        return $this->belongsTo(
            TrainingModule::class,
            'training_module_id'
        );
    }
}
