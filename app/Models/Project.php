<?php

namespace App\Models;

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
        'is_featured'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'description' => 'array',
        ];
    }


    public function getIsFeaturedAttribute($value)
    {
        return $value === 1;
    }

    public function setIsFeaturedAttribute($value)
    {
        $this->attributes['is_featured'] = $value ? 1 : 0;
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function projectMedias(): HasMany
    {
        return $this->hasMany(ProjectMedia::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'project_categories');
    }

    public function techStacks(): BelongsToMany
    {
        return $this->belongsToMany(TechStack::class, 'project_tech_stacks');
    }
}
