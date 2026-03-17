<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMedia extends Model
{
    protected $fillable = [
        'project_id',
        'media_type',
        'media_url',
        'media_description',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
