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
        // Saves a copy of some ride request data just in case the ride request is deleted.
        Schema::create('profit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('ride_id')->nullable();
            $table->unsignedBigInteger('ride_request_id')->nullable();
            $table->string('from_address')->nullable();
            $table->decimal('from_latitude', 10, 7)->nullable();
            $table->decimal('from_longitude', 10, 7)->nullable();
            $table->string('to_address')->nullable();
            $table->decimal('to_latitude', 10, 7)->nullable();
            $table->decimal('to_longitude', 10, 7)->nullable();
            $table->decimal('profit', 10, 2); //default: PHP
            $table->timestamps();
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->foreign('ride_id')->references('id')->on('rides')->onDelete('set null');
            $table->foreign('ride_request_id')->references('id')->on('ride_requests')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profit_logs', function(Blueprint $table){
            $table->dropForeign(['ride_request_id']);
            $table->dropForeign(['ride_id']);
            $table->dropForeign(['driver_id']);
        });
        Schema::dropIfExists('profit_logs');
    }
};
