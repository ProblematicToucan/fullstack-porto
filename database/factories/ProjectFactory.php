<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->regexify('[a-z0-9]{6}'),
            'description' => ['en' => fake()->paragraphs(2, true)],
            'project_url' => fake()->optional(0.7)->url(),
            'repo_url' => fake()->optional(0.5)->url(),
            'image' => fake()->optional(0.5)->imageUrl(),
            'is_featured' => fake()->boolean(20),
        ];
    }
}
