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
        Schema::table('saved_rides', function(Blueprint $table){
            $table->dropForeign(['saved_ride_folder_id']);
            $table->dropColumn('saved_ride_folder_id');
        });

        Schema::create('saved_ride_folder_items', function(Blueprint $table){
            $table->unsignedBigInteger('saved_ride_id');
            $table->unsignedBigInteger('saved_ride_folder_id');
            $table->primary(['saved_ride_id', 'saved_ride_folder_id']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('saved_rides', function(Blueprint $table){
            $table->unsignedBigInteger('saved_ride_folder_id');
            $table->foreign('saved_ride_folder_id')->references('id')->on('saved_ride_folders')->onDelete('cascade');
        });

        Schema::dropIfExists('saved_ride_folder_items');
    }
};
