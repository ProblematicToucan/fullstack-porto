<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Full-text search against a pre-built PostgreSQL tsvector column.
 *
 * WHY this exists instead of just calling whereFullText():
 *  - whereFullText() computes to_tsvector() at query time — it CANNOT use a GIN index.
 *  - Supabase/Postgres FTS is fast because you create a generated tsvector column
 *    and build a GIN index on it. Queries must then use `column @@ query` directly.
 *  - This trait encapsulates that raw SQL pattern so you don't repeat it everywhere,
 *    and adds orderByRelevance (ts_rank) which whereFullText has no equivalent for.
 *
 * Setup in your model:
 *   protected string $fullTextColumn = 'search_vector'; // your tsvector column name
 *   protected string $searchLanguage = 'english';       // optional, default 'english'
 *
 * Usage:
 *   Post::search('laravel tips')->paginate();
 *   Post::search('laravel tips')->orderByRelevance('laravel tips')->paginate();
 *   Post::public()->search('laravel tips')->latest()->get();
 */
trait HasFullTextSearch
{
    /**
     * The pre-built tsvector column to query against.
     * Must have a GIN index in your migration for this to be fast.
     *
     * Override in your model:
     *   protected string $fullTextColumn = 'search_vector';
     */
    protected string $fullTextColumn = 'search_vector';

    /**
     * PostgreSQL text-search configuration / language.
     * Must match the language used when the tsvector column was built.
     * Supabase defaults to 'english'.
     */
    protected string $searchLanguage = 'english';

    /**
     * Scope: filter rows matching the search term via the GIN-indexed tsvector column.
     *
     * Emits: WHERE search_vector @@ websearch_to_tsquery('english', ?)
     *
     * Uses websearch_to_tsquery so users get:
     *   - quoted phrases: "exact match"
     *   - OR operator:    laravel OR django
     *   - exclusions:     laravel -php
     *
     * @param  Builder  $query
     * @param  string  $term  Raw user input — safely parameterized, not interpolated.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        return $query->whereRaw(
            "{$this->fullTextColumn} @@ websearch_to_tsquery(?, ?)",
            [$this->searchLanguage, $term]
        );
    }

    /**
     * Scope: order results by FTS relevance (most relevant first).
     *
     * Emits: ORDER BY ts_rank(search_vector, websearch_to_tsquery('english', ?)) DESC
     *
     * Always pair with scopeSearch() — ordering without filtering is a full-table scan.
     *
     * @param  Builder  $query
     * @param  string  $term  Same term passed to scopeSearch().
     */
    public function scopeOrderByRelevance(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        return $query->orderByRaw(
            "ts_rank({$this->fullTextColumn}, websearch_to_tsquery(?, ?)) DESC",
            [$this->searchLanguage, $term]
        );
    }
}
