<?php

namespace App\Ai\Tools;

use App\Ai\Knowledge\PortfolioContentExtractor;
use App\Models\Project;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ListPortfolioProjects implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'List all public portfolio projects (newest first). Returns title, slug, categories, tech stack, a short text summary, and links. Use before answering questions about what projects exist or to compare work.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $projects = Project::query()
            ->with(['categories', 'techStacks'])
            ->latest()
            ->get();

        if ($projects->isEmpty()) {
            return 'No projects are published on this portfolio yet.';
        }

        $payload = $projects->map(function (Project $project): array {
            $plain = PortfolioContentExtractor::plainTextFromBuilderContent($project->description);

            return [
                'title' => $project->title,
                'slug' => $project->slug,
                'is_featured' => $project->is_featured,
                'categories' => $project->categories->pluck('name')->values()->all(),
                'tech_stack' => $project->techStacks->pluck('name')->values()->all(),
                'summary' => Str::limit($plain, 400),
                'project_url' => $project->project_url,
                'repo_url' => $project->repo_url,
                'portfolio_page_url' => route('project.show', $project),
            ];
        });

        return "Portfolio projects (newest first):\n\n".$payload->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     *
     * OpenAI strict mode requires a non-empty parameters object with additionalProperties: false.
     * laravel/ai only adds that block when the schema is non-empty (see laravel/ai#313); a minimal
     * required flag keeps the wire format valid until a released SDK fix.
     *
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'list_public_projects' => $schema->boolean()
                ->description('Must be true. Requests the full public project list.')
                ->required(),
        ];
    }
}
