<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiLedger extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'training_module_id',
        'source',
        'points',
        'description',
        'earned_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'points' => 'integer',

            'earned_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
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
            TrainingModule::class
        );
    }
}