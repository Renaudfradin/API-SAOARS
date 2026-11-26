<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->string('artwork')->nullable();
            $table->string('artwork_thumbnail')->nullable();
            $table->string('artwork_large')->nullable();
            $table->string('artwork_limit_break')->nullable();
            $table->string('artwork_limit_break_thumbnail')->nullable();
        });

        Schema::table('home_characters', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('image_large')->nullable();
        });

        Schema::table('home_backgrounds', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('image_large')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn([
                'artwork',
                'artwork_thumbnail',
                'artwork_large',
                'artwork_limit_break',
                'artwork_limit_break_thumbnail',
            ]);
        });

        Schema::table('home_characters', function (Blueprint $table) {
            $table->dropColumn(['image', 'image_large']);
        });

        Schema::table('home_backgrounds', function (Blueprint $table) {
            $table->dropColumn(['image', 'image_large']);
        });
    }
};
