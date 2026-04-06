<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE knowledge_chunks
            ADD COLUMN search_vector tsvector
            GENERATED ALWAYS AS (
                to_tsvector('english', coalesce(content, ''))
            ) STORED
        ");

        DB::statement("
            CREATE INDEX knowledge_chunks_search_vector_gin
            ON knowledge_chunks USING GIN (search_vector)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS knowledge_chunks_search_vector_gin');

        Schema::table('knowledge_chunks', function (Blueprint $table) {
            $table->dropColumn('search_vector');
        });
    }
};
