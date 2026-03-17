<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'project_url',
        'repo_url',
        'image',
        'is_featured',
    ];

    protected $casts = [
        'description' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'project_categories');
    }

    public function techStacks(): BelongsToMany
    {
        return $this->belongsToMany(TechStack::class, 'project_tech_stacks');
    }

    public function projectMedias(): HasMany
    {
        return $this->hasMany(ProjectMedia::class);
    }

    protected function isFeatured(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (bool) $value,
            set: fn ($value) => (bool) $value,
        );
    }
}
