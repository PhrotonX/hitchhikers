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
        Schema::table('messages', function(Blueprint $table){
            $table->dropColumn('sender_id');
            $table->unsignedBigInteger('passenger_id');
            $table->unsignedBigInteger('driver_id');
            $table->foreign('passenger_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function(Blueprint $table){
            $table->dropForeign(['passenger_id']);
            $table->dropForeign(['driver_id']);
            $table->dropColumn('passenger_id');
            $table->dropColumn('driver_id');
            $table->dropColumn('sender_id');
        });
    }
};
