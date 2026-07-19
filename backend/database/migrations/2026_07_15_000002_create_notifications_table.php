<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // article, journalist, category, ad
            $table->string('action'); // created, updated, published
            $table->string('title'); // Brief notification title
            $table->text('body')->nullable(); // Optional description
            $table->morphs('notifiable'); // link to the actual resource (article, journalist, etc)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // who triggered it
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
