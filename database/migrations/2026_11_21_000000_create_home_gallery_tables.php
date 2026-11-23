<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_characters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fanadata_id')->unique();
            $table->string('person_key')->index();
            $table->json('person_name');
            $table->json('title');
            $table->unsignedTinyInteger('type')->nullable();
            $table->timestamps();
        });

        Schema::create('home_backgrounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fanadata_id')->unique();
            $table->json('name');
            $table->unsignedTinyInteger('world')->nullable()->index();
            $table->unsignedTinyInteger('rarity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_backgrounds');
        Schema::dropIfExists('home_characters');
    }
};
