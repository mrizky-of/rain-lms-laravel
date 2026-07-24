<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(
            User::class
        );
    }

    public function trainingModules(): BelongsToMany
    {
        return $this->belongsToMany(
            TrainingModule::class,
            'department_training_module'
        )->withTimestamps();
    }

    public function funTests(): HasMany
    {
        return $this->hasMany(
            FunTestQuestion::class
        );
    }
}
