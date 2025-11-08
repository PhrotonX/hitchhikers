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
        Schema::create('saved_ride_folders', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('color');
            $table->string('icon');
            $table->text('description');
        });

        Schema::create('saved_rides', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('ride_id')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('saved_ride_folder_id');
            $table->foreign('ride_id')->references('id')->on('rides')->onDelete('cascade');
            $table->foreign('saved_ride_folder_id')->references('id')->on('saved_ride_folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saved_rides', function(Blueprint $table){
            $table->dropForeign(['ride_id']);
            $table->dropForeign(['saved_ride_folder_id']);
        });
        Schema::dropIfExists('saved_rides');
        Schema::dropIfExists('saved_ride_folders');
    }
};
