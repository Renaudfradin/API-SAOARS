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
        Schema::create('weapons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('type');
            $table->string('element_weapons');
            $table->integer('hp');
            $table->integer('mp');
            $table->integer('atk');
            $table->integer('matk');
            $table->integer('def');
            $table->integer('mdef');
            $table->integer('crit');
            $table->integer('spd');
            $table->string('effect_1')->nullable();
            $table->string('effect_2')->nullable();
            $table->string('effect_3')->nullable();
            $table->integer('characters_id')->nullable();
            $table->string('image')->nullable();
            $table->integer('start');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapons');
    }
};
