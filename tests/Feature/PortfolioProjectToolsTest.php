<?php

use App\Ai\Tools\GetPortfolioProject;
use App\Ai\Tools\ListPortfolioProjects;
use App\Models\Project;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use Illuminate\Support\Str;
use Laravel\Ai\Tools\Request;

it('lists portfolio projects as JSON with routes and summaries', function () {
    Project::factory()->create([
        'title' => 'Alpha App',
        'slug' => 'alpha-app',
        'description' => [
            ['type' => 'paragraph', 'data' => ['text' => '<p>Alpha description text here.</p>']],
        ],
    ]);

    $tool = new ListPortfolioProjects;
    $out = (string) $tool->handle(new Request);

    expect($out)->toContain('Alpha App')
        ->and($out)->toContain('alpha-app')
        ->and($out)->toContain('Alpha description text here');

    /** @var array<int, array<string, mixed>> $rows */
    $rows = json_decode(Str::after($out, "\n\n"), true, 512, JSON_THROW_ON_ERROR);
    expect($rows[0]['portfolio_page_url'])->toContain('alpha-app');

    $schema = new JsonSchemaTypeFactory;
    expect($tool->schema($schema))->toHaveKey('list_public_projects');
});

it('returns a message when no projects exist', function () {
    $tool = new ListPortfolioProjects;
    $out = (string) $tool->handle(new Request);

    expect($out)->toBe('No projects are published on this portfolio yet.');
});

it('loads one project by slug with plain description and media', function () {
    $project = Project::factory()->create([
        'title' => 'Beta Tool',
        'slug' => 'beta-tool',
        'description' => [
            ['type' => 'paragraph', 'data' => ['text' => '<p>Beta body content.</p>']],
        ],
    ]);

    $project->projectMedias()->create([
        'media_type' => 'image',
        'media_url' => 'https://example.com/shot.png',
        'media_description' => 'Screenshot',
    ]);

    $tool = new GetPortfolioProject;
    $out = (string) $tool->handle(new Request(['slug' => 'beta-tool']));

    $decoded = json_decode($out, true, 512, JSON_THROW_ON_ERROR);

    expect($decoded['title'])->toBe('Beta Tool')
        ->and($decoded['slug'])->toBe('beta-tool')
        ->and($decoded['description_plain'])->toContain('Beta body content')
        ->and($decoded['portfolio_page_url'])->toContain('beta-tool')
        ->and($decoded['media'])->toHaveCount(1)
        ->and($decoded['media'][0]['url'])->toBe('https://example.com/shot.png');

    $schema = new JsonSchemaTypeFactory;
    $fields = $tool->schema($schema);
    expect($fields)->toHaveKey('slug');
});

it('explains when a project slug is missing or unknown', function () {
    $tool = new GetPortfolioProject;

    expect((string) $tool->handle(new Request([])))->toContain('Missing project slug');

    expect((string) $tool->handle(new Request(['slug' => 'nope'])))->toContain('No project found');
});
