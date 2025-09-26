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
        Schema::table('rides', function(Blueprint $table){
            $table->unsignedBigInteger('vehicle_id');
            $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riders', function(Bludeprint $table){
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn('vehicle_id');
            $table->unsignedBigInteger('driver_id');
            $table->foreign('driver_id')->references('id')->on('drivers');
        });
    }
};
