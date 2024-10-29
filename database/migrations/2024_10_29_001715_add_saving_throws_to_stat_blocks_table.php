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
        Schema::table('stat_blocks', function (Blueprint $table) {
            $table->string('saving_throws')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stat_blocks', function (Blueprint $table) {
            $table->dropColumn('saving_throws');
        });
    }
};
