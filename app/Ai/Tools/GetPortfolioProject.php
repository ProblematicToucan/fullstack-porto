<?php

namespace App\Ai\Tools;

use App\Ai\Knowledge\PortfolioContentExtractor;
use App\Models\Project;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetPortfolioProject implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Load full details for one portfolio project by its URL slug. Returns plain-text description, categories, tech stack with optional notes, media items, and external links. Use when the user asks about a specific project or after listing projects to drill into one.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $slug = trim((string) ($request->all()['slug'] ?? ''));

        if ($slug === '') {
            return 'Missing project slug. Pass the slug from the project listing or the site URL (e.g. the segment after /project/).';
        }

        $project = Project::query()
            ->where('slug', $slug)
            ->with(['categories', 'techStacks', 'projectMedias'])
            ->first();

        if ($project === null) {
            return 'No project found with slug "'.$slug.'". Use the list portfolio projects tool to see valid slugs.';
        }

        $body = PortfolioContentExtractor::plainTextFromBuilderContent($project->description);

        $data = [
            'title' => $project->title,
            'slug' => $project->slug,
            'is_featured' => $project->is_featured,
            'categories' => $project->categories->pluck('name')->values()->all(),
            'tech_stack' => $project->techStacks->map(fn ($tech): array => [
                'name' => $tech->name,
                'description' => $tech->description,
            ])->values()->all(),
            'description_plain' => $body,
            'project_url' => $project->project_url,
            'repo_url' => $project->repo_url,
            'portfolio_page_url' => route('project.show', $project),
            'media' => $project->projectMedias->map(fn ($media): array => [
                'type' => $media->media_type,
                'url' => $media->media_url,
                'description' => $media->media_description,
            ])->values()->all(),
        ];

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    /**
     * Get the tool's schema definition.
     *
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'slug' => $schema->string()
                ->description('The project slug from the portfolio URL path (/project/{slug}).')
                ->required(),
        ];
    }
}
