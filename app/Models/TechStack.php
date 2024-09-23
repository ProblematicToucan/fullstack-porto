<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TechStack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'description'
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_tech_stack');
    }
}
