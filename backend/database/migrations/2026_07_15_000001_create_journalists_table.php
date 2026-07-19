<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journalists', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('journalist_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journalist_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10)->index();
            $table->string('name');
            $table->text('bio')->nullable();
            $table->unique(['journalist_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journalist_translations');
        Schema::dropIfExists('journalists');
    }
};
