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
            $table->unsignedBigInteger('driver_id');
            // $table->foreign('driver_id')->references('id')->on('drivers');
        });

        Schema::table('vehicles', function(Blueprint $table){
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function(Blueprint $table){
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });

        Schema::table('rides', function(Blueprint $table){
            // $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
        });
    }
};
