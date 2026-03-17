<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->regexify('[a-z0-9]{6}'),
            'is_public' => true,
            'content' => fake()->paragraphs(3, true),
        ];
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => ['is_public' => false]);
    }
}
