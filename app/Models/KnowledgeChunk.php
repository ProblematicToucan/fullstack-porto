<?php

namespace App\Models;

use Database\Factories\KnowledgeChunkFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeChunk extends Model
{
    /** @use HasFactory<KnowledgeChunkFactory> */
    use HasFactory;

    protected $fillable = [
        'post_id',
        'project_id',
        'chunk_index',
        'content',
        'embedding',
        'is_deleted',
    ];

    protected function casts(): array
    {
        return [
            'embedding' => 'array',
            'is_deleted' => 'boolean',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeSearchable(Builder $query): Builder
    {
        return $query->where('is_deleted', false);
    }
}
