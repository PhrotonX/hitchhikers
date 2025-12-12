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
        // rides table
        Schema::table('rides', function (Blueprint $table) {
            $table->foreign('driver_id')
                  ->references('id')
                  ->on('drivers')
                  ->onDelete('set null');
            
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('vehicles')
                  ->onDelete('restrict');
        });

        // vehicles table
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreign('driver_id')
                  ->references('id')
                  ->on('drivers')
                  ->onDelete('cascade');
        });

        // reviews table
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('account_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('ride_id')
                  ->references('id')
                  ->on('rides')
                  ->onDelete('cascade');
        });

        // replies table
        Schema::table('replies', function (Blueprint $table) {
            $table->foreign('account_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('review_id')
                  ->references('id')
                  ->on('reviews')
                  ->onDelete('cascade');
            
            $table->foreign('ride_id')
                  ->references('id')
                  ->on('rides')
                  ->onDelete('cascade');
        });

        // reports table
        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('reporter_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('reported_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // passengers table
        Schema::table('passengers', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // user_addresses table
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('address_id')
                  ->references('id')
                  ->on('addresses')
                  ->onDelete('cascade');
        });

        // messages table
        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('sender_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('receiver_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('conversation_id')
                  ->references('id')
                  ->on('conversations')
                  ->onDelete('cascade');
        });

        // vehicle_drivers table
        Schema::table('vehicle_drivers', function (Blueprint $table) {
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('vehicles')
                  ->onDelete('cascade');
            
            $table->foreign('driver_id')
                  ->references('id')
                  ->on('drivers')
                  ->onDelete('cascade');
        });

        // ride_destinations table
        Schema::table('ride_destinations', function (Blueprint $table) {
            $table->foreign('ride_id')
                  ->references('id')
                  ->on('rides')
                  ->onDelete('cascade');
        });

        // saved_rides table
        Schema::table('saved_rides', function (Blueprint $table) {
            $table->foreign('ride_id')
                  ->references('id')
                  ->on('rides')
                  ->onDelete('cascade');
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('folder_id')
                  ->references('id')
                  ->on('saved_ride_folders')
                  ->onDelete('set null');
        });

        // ride_requests table
        Schema::table('ride_requests', function (Blueprint $table) {
            $table->foreign('ride_id')
                  ->references('id')
                  ->on('rides')
                  ->onDelete('cascade');
            
            $table->foreign('passenger_id')
                  ->references('id')
                  ->on('passengers')
                  ->onDelete('cascade');
            
            $table->foreign('destination_id')
                  ->references('id')
                  ->on('ride_destinations')
                  ->onDelete('set null');
        });

        // profit_logs table
        Schema::table('profit_logs', function (Blueprint $table) {
            $table->foreign('driver_id')
                  ->references('id')
                  ->on('drivers')
                  ->onDelete('cascade');
            
            $table->foreign('ride_id')
                  ->references('id')
                  ->on('rides')
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

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropForeign(['ride_id']);
        });

        Schema::table('replies', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropForeign(['review_id']);
            $table->dropForeign(['ride_id']);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['reporter_id']);
            $table->dropForeign(['reported_id']);
        });

        Schema::table('passengers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['address_id']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);
            $table->dropForeign(['conversation_id']);
        });

        Schema::table('vehicle_drivers', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['driver_id']);
        });

        Schema::table('ride_destinations', function (Blueprint $table) {
            $table->dropForeign(['ride_id']);
        });

        Schema::table('saved_rides', function (Blueprint $table) {
            $table->dropForeign(['ride_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['folder_id']);
        });

        Schema::table('ride_requests', function (Blueprint $table) {
            $table->dropForeign(['ride_id']);
            $table->dropForeign(['passenger_id']);
            $table->dropForeign(['destination_id']);
        });

        Schema::table('profit_logs', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['ride_id']);
        });
    }
};