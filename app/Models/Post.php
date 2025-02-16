<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $title
 * @property string $slug
 * @property bool $is_public
 * @property string $content
 */
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

    public function getIsPublicAttribute($value): bool
    {
        return $value === 1;
    }

    public function setIsPublicAttribute($value): void
    {
        $this->attributes['is_public'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getUpdatedAtAttribute($value): string
    {
        return Carbon::parse($value)->diffForHumans();
    }
}
