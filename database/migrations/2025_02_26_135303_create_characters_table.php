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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('slug');
            $table->text('description');
            $table->text('profile');
            $table->string('element');
            $table->string('weapon_type');
            $table->integer('atk1');
            $table->integer('atk2')->nullable();
            $table->integer('atk3')->nullable();
            $table->integer('hp');
            $table->integer('mp');
            $table->integer('atk');
            $table->integer('matk');
            $table->integer('def');
            $table->integer('mdef');
            $table->integer('crit');
            $table->integer('spd');
            $table->text('ultime');
            $table->text('ultime_description');
            $table->text('enhance')->nullable();
            $table->integer('enhance_atk')->nullable();
            $table->integer('enhance_atk2')->nullable();
            $table->integer('start');
            $table->integer('cost');
            $table->string('special_partner')->nullable();
            $table->text('image');
            $table->text('image2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
