<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FunTestQuestion extends Model
{
    protected $fillable = [
        'mode',
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
}
