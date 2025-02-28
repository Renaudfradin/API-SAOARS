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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('type_equipment');
            $table->integer('hp');
            $table->integer('mp');
            $table->integer('atk');
            $table->integer('matk');
            $table->integer('def');
            $table->integer('mdef');
            $table->integer('crit');
            $table->integer('spd');
            $table->text('effect_1');
            $table->text('effect_2');
            $table->integer('start');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
