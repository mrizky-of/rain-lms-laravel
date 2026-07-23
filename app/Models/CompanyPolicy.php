<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyPolicy extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'legal_reference',
        'category',
        'target',
        'version',
        'effective_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'effective_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function acknowledgements(): HasMany
    {
        return $this->hasMany(
            PolicyAcknowledgement::class
        );
    }
}
