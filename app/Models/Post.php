<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'is_public',
        'content',
    ];

    protected $casts = [
        'content' => 'string',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }
}
