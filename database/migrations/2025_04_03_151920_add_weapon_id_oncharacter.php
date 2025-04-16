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
        Schema::table('characters', function (Blueprint $table) {
            $table->string('weapon_id')->nullable();
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->text('effect_1')->nullable()->change();
            $table->text('effect_2')->nullable()->change();
        });

        // Schema::table('imaginations', function (Blueprint $table) {
        //     $table->text('character')->nullable();
        //     $table->text('image')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('weapon_id');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn('effect_1');
            $table->dropColumn('effect_2');
        });

        Schema::table('imaginations', function (Blueprint $table) {
            $table->dropColumn('character');
            $table->dropColumn('image');
        });
    }
};
