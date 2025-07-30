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
        Schema::table('weapons', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('image2')->nullable();
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('image2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weapons', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('image2');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('image2');
        });
    }
};
