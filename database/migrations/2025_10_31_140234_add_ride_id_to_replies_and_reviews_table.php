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
        Schema::table('replies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('ride_id')->nullable();
            $table->foreign('ride_id')->references('id')->on('rides')->onDelete('cascade');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('ride_id')->nullable();
            $table->foreign('ride_id')->references('id')->on('rides')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->dropForeign(['ride_id']);
            $table->dropColumn('ride_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['ride_id']);
            $table->dropColumn('ride_id');
        });
    }
};
