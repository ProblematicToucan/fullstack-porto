<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'is_public',
        'content',
    ];

    public function getIsDraftAttribute($value): bool
    {
        return $value === 1;
    }

    public function setIsDraftAttribute($value): void
    {
        $this->attributes['is_draft'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return  'slug';
    }
}
