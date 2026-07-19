<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the foreign key that cascades on delete
        Schema::table('articles', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['author_id']);
        });

        // Set any author_id values that reference non-existent users to null
        DB::statement('UPDATE articles SET author_id = NULL WHERE author_id IS NOT NULL AND author_id NOT IN (SELECT id FROM users)');

        // Re-add the foreign key with SET NULL instead of CASCADE
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable()->change();
            $table->foreign('author_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
        });
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
        });
    }
};
