<?php

namespace App\Ai\Tools;

use App\Models\TechStack;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ListTechStacks implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'List the site owner tech stacks from the tech_stacks table. Returns canonical stack metadata (name, slug, logo, description). Use this when visitors ask about skills, technologies, or stack expertise.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $stacks = TechStack::query()
            ->orderBy('name')
            ->get(['name', 'slug', 'logo', 'description']);

        if ($stacks->isEmpty()) {
            return 'No tech stacks are listed in the owner profile yet.';
        }

        return "Owner tech stacks (from tech_stacks table):\n\n".$stacks->values()->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     *
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'list_owner_tech_stacks' => $schema->boolean()
                ->description('Must be true. Requests the full owner tech stack list from the tech_stacks table.')
                ->required(),
        ];
    }
}
