<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        'content' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post): void {
            if (filled($post->title) && (string) $post->slug === '') {
                $base = Str::slug($post->title);
                $post->slug = $base;
                $count = 0;
                while (static::query()->where('slug', $post->slug)->when($post->exists, fn ($q) => $q->whereKeyNot($post->getKey()))->exists()) {
                    $post->slug = $base . '-' . (++$count);
                }
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }
}
