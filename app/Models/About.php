<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $table = 'about';

    protected $fillable = [
        'heading',
        'body',
        'avatar',
        'links',
    ];

    protected $casts = [
        'links' => 'array',
    ];
}
