<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('query_logs', function (Blueprint $table) {
            $table->id();
            $table->string('query', 255);
            $table->string('type', 50);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('top_queries_statistics', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('query_logs');
        Schema::dropIfExists('top_queries_statistics');
    }
}; 