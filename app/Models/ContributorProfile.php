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
        'rating',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trainingModules(): BelongsToMany
    {
        return $this->belongsToMany(
            TrainingModule::class,
            'contributor_training_module'
        )->withTimestamps();
    }
}
