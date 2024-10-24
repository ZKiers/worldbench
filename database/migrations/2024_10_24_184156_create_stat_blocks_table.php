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
        Schema::create('stat_blocks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');

            $table->integer('armor_class');
            $table->integer('hit_points');
            $table->string('speed');

            $table->string('stats');
            $table->string('skills')->nullable();

            $table->integer('challenge')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stat_blocks');
    }
};
