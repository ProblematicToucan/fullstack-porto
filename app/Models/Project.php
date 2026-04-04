<?php

namespace App\Models;

use App\Observers\ProjectKnowledgeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[ObservedBy([ProjectKnowledgeObserver::class])]
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
        'knowledge_source_hash',
    ];

    protected $casts = [
        'description' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (Project $project): void {
            if (filled($project->title) && (string) $project->slug === '') {
                $base = Str::slug($project->title);
                $project->slug = $base;
                $count = 0;
                while (static::query()->where('slug', $project->slug)->when($project->exists, fn ($q) => $q->whereKeyNot($project->getKey()))->exists()) {
                    $project->slug = $base.'-'.(++$count);
                }
            }
        });
    }

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

    public function knowledgeChunks(): HasMany
    {
        return $this->hasMany(KnowledgeChunk::class);
    }

    protected function isFeatured(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (bool) $value,
            set: fn ($value) => (bool) $value,
        );
    }
}
