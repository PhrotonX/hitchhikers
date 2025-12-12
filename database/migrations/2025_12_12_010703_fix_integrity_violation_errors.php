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
        Schema::table('rides', function (Blueprint $table) {
            $table->foreign('driver_id')
                  ->references('id')
                  ->on('drivers')
                  ->onDelete('cascade');
        });
        
        // rides.vehicle_id - already exists, need to update to cascade
        Schema::table('rides', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
        });
        
        Schema::table('rides', function (Blueprint $table) {
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('vehicles')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['vehicle_id']);
        });
        
        // Restore original vehicle_id foreign key without cascade
        Schema::table('rides', function (Blueprint $table) {
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('vehicles');
        });
    }
};