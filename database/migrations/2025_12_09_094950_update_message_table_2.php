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
            $table->unsignedBigInteger('ride_request_id');
            $table->foreign('ride_request_id')->references('id')->on('ride_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function(Blueprint $table){
            $table->dropForeign(['ride_request_id']);
            $table->dropColumn('ride_request_id');
        });
    }
};
