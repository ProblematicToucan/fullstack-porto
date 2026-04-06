<?php

namespace App\Ai\Tools;

use App\Models\KnowledgeChunk;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Laravel\Ai\Tools\SimilaritySearch;
use Stringable;

class PortfolioKnowledgeSearch implements Tool
{
    private readonly SimilaritySearch $similaritySearch;

    public function __construct()
    {
        $this->similaritySearch = new SimilaritySearch(function (string $query) {
            // 1. Run Semantic Search
            $semanticChunks = KnowledgeChunk::query()
                ->searchable()
                ->whereVectorSimilarTo('embedding', $query, 0.4)
                ->limit(10)
                ->get();

            // 2. Run Full-Text Search (exact match / keyword)
            $ftsChunks = KnowledgeChunk::query()
                ->searchable()
                ->search($query)                // Hits tsvector GIN index
                ->orderByRelevance($query)      // sort by exact phrases
                ->limit(10)
                ->get();

            // 3. Merge, remove duplicates, and remove the heavy embedding array before sending to LLM
            return $semanticChunks
                ->concat($ftsChunks)
                ->unique('id')
                ->take(15);
        });

        $this->similaritySearch->withDescription(
            'Search indexed text from this portfolio\'s public blog posts and projects. Use for factual questions about posts, projects, or the site owner\'s work.'
        );
    }

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return $this->similaritySearch->description();
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        return $this->similaritySearch->handle($request);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return $this->similaritySearch->schema($schema);
    }
}
