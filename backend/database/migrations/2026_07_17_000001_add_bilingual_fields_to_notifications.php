<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_ckb')->nullable()->after('title_en');
            $table->text('body_en')->nullable()->after('body');
            $table->text('body_ckb')->nullable()->after('body_en');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'title_ckb', 'body_en', 'body_ckb']);
        });
    }
};
