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
        if (DB::connection()->getDriverName() === 'pgsql') {
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
        } else {
            // For SQLite tests, just add a basic column to prevent missing column errors
            Schema::table('knowledge_chunks', function (Blueprint $table) {
                $table->text('search_vector')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS knowledge_chunks_search_vector_gin');
        }

        Schema::table('knowledge_chunks', function (Blueprint $table) {
            $table->dropColumn('search_vector');
        });
    }
};
