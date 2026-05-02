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
 *   // Full Text Search (tsvector)
 *   Post::search('laravel tips')->paginate();
 *   Post::search('laravel tips')->orderByRelevance('laravel tips')->paginate();
 *   
 *   // Fuzzy Search (pg_trgm - requires pg_trgm extension and index)
 *   Post::whereSimilar('content', 'larvel')->orderBySimilarity('content', 'larvel')->get();
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

        if ($query->getConnection()->getDriverName() !== 'pgsql') {
            return $query->where('content', 'like', "%{$term}%")
                         ->orWhere('title', 'like', "%{$term}%"); // generic fallback
        }

        $column = $query->getQuery()->getGrammar()->wrap($this->fullTextColumn);

        return $query->whereRaw(
            "{$column} @@ websearch_to_tsquery(?, ?)",
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

        if ($term === '' || $query->getConnection()->getDriverName() !== 'pgsql') {
            return $query; // Sorting by relevance doesn't apply cleanly in SQLite fallback
        }

        $column = $query->getQuery()->getGrammar()->wrap($this->fullTextColumn);

        return $query->orderByRaw(
            "ts_rank({$column}, websearch_to_tsquery(?, ?)) DESC",
            [$this->searchLanguage, $term]
        );
    }

    /**
     * Scope: filter rows using pg_trgm word similarity (Fuzzy Search for long text).
     *
     * Note: This requires the `pg_trgm` Postgres extension to be enabled,
     * and ideally a GIN index using `gin_trgm_ops` on the column.
     * 
     * Uses `word_similarity` (<%) instead of `similarity` (%), which finds the 
     * most similar word/phrase inside a long block of text rather than comparing 
     * the length of the entire document to the short search query.
     *
     * @param  Builder  $query
     * @param  string  $column  The raw text column to search (e.g., 'title' or 'content')
     * @param  string  $term    The search term (typos allowed)
     * @param  float   $threshold Minimum similarity score (0.0 to 1.0)
     */
    public function scopeWhereSimilar(Builder $query, string $column, string $term, float $threshold = 0.3): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        if ($query->getConnection()->getDriverName() !== 'pgsql') {
            return $query->where($column, 'like', "%{$term}%");
        }

        $wrappedColumn = $query->getQuery()->getGrammar()->wrap($column);

        // The <% operator means "term has a word_similarity match in the column text".
        // We override the default threshold dynamically per query.
        return $query->whereRaw("? <% {$wrappedColumn}", [$term])
                     ->whereRaw("word_similarity(?, {$wrappedColumn}) >= ?", [$term, $threshold]);
    }

    /**
     * Scope: order results by pg_trgm word similarity (closest substring match first).
     *
     * Emits: ORDER BY word_similarity(term, column) DESC
     */
    public function scopeOrderBySimilarity(Builder $query, string $column, string $term): Builder
    {
        $term = trim($term);

        if ($term === '' || $query->getConnection()->getDriverName() !== 'pgsql') {
            return $query;
        }

        $wrappedColumn = $query->getQuery()->getGrammar()->wrap($column);

        // word_similarity requires (search_term, document_text)
        return $query->orderByRaw("word_similarity(?, {$wrappedColumn}) DESC", [$term]);
    }
}
