<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyContent extends Model
{
    protected $fillable = [
        'key',
        'title',
        'content',
    ];
}
