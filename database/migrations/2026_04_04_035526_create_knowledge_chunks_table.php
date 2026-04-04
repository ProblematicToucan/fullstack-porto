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
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            Schema::ensureVectorExtensionExists();

            Schema::create('knowledge_chunks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->nullable()->constrained()->cascadeOnDelete();
                $table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
                $table->unsignedInteger('chunk_index');
                $table->text('content');
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();
            });

            $connection = Schema::getConnection();
            $grammar = $connection->getQueryGrammar();
            $tableName = 'knowledge_chunks';
            $tableSql = $grammar->wrapTable($tableName);
            $embeddingSql = $grammar->wrap('embedding');

            DB::statement("alter table {$tableSql} add column {$embeddingSql} extensions.vector(1536) not null");

            // Supabase (and similar) install pgvector in the `extensions` schema; unqualified
            // `vector_cosine_ops` fails for HNSW (see PostgreSQL search_path vs extension schema).
            $tableForIndexName = $connection->getConfig('prefix_indexes')
                ? (str_contains($tableName, '.')
                    ? substr_replace($tableName, '.'.$connection->getTablePrefix(), strrpos($tableName, '.'), 1)
                    : $connection->getTablePrefix().$tableName)
                : $tableName;
            $vectorIndexName = str_replace(['-', '.'], '_', strtolower("{$tableForIndexName}_embedding_vectorindex"));
            $indexSql = $grammar->wrap($vectorIndexName);

            DB::statement("create index {$indexSql} on {$tableSql} using hnsw ({$embeddingSql} extensions.vector_cosine_ops)");
        } else {
            Schema::create('knowledge_chunks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->nullable()->constrained()->cascadeOnDelete();
                $table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
                $table->unsignedInteger('chunk_index');
                $table->text('content');
                $table->json('embedding');
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_chunks');
    }
};
