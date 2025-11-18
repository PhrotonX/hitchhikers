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
        Schema::create('ride_requests', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('sender_user_id');
            $table->unsignedBigInteger('ride_id');
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->decimal('from_latitude', 10, 7)->nullable();
            $table->decimal('from_longitude', 10, 7)->nullable();
            $table->decimal('to_latitude', 10, 7)->nullable();
            $table->decimal('to_longitude', 10, 7)->nullable();
            $table->string('pickup_at');
            $table->time('time');
            $table->text('message')->nullable();
            $table->timestamps();
            $table->datetime('status_updated_at');
            $table->string('status');
            $table->foreign('sender_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ride_id')->references('id')->on('rides')->onDelete('cascade');
            $table->foreign('destination_id')->references('id')->on('ride_destinations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_requests');
    }
};
