<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TechStack extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_tech_stacks');
    }
}
