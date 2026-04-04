<?php

use App\Ai\Tools\PortfolioKnowledgeSearch;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;

it('exposes the portfolio knowledge search description and query schema', function () {
    $tool = new PortfolioKnowledgeSearch;

    expect($tool->description())
        ->toContain('portfolio')
        ->and($tool->description())->toContain('blog posts');

    $schema = new JsonSchemaTypeFactory;
    $fields = $tool->schema($schema);

    expect($fields)->toHaveKey('query');
});
