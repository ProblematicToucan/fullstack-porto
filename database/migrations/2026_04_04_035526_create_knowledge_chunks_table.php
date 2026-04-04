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
                $table->vector('embedding', 1536)->index();
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();
            });
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
