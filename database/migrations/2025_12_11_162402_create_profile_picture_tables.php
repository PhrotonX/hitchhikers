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
        Schema::dropIfExists('pictures');

        Schema::create('pictures', function (Blueprint $table) {
            $table->id('picture_id');
            $table->string('picture_path')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('alt_text')->nullable();
        });

        Schema::create('profile_pictures', function (Blueprint $table) {
            $table->id('pfp_id');
            $table->unsignedBigInteger('picture_id');
            $table->string('pfp_xs')->nullable();
            $table->string('pfp_small')->nullable();
            $table->string('pfp_medium')->nullable();
            $table->string('pfp_large')->nullable();

            $table->foreign('picture_id')->references('picture_id')->on('pictures')->onDelete('cascade');
        });

        Schema::create('user_profile_picture', function (Blueprint $table) {
            $table->unsignedBigInteger('pfp_id');
            $table->unsignedBigInteger('user_id');

            $table->primary(['pfp_id', 'user_id']);

            $table->foreign('pfp_id')->references('pfp_id')->on('profile_pictures')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('users', function(Blueprint $table){
            $table->unsignedBigInteger('profile_picture_id')->nullable();
            $table->foreign('profile_picture_id')->references('pfp_id')->on('user_profile_picture');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function(Blueprint $table){
            $table->dropForeign(['profile_picture_id']);
            $table->dropColumn('profile_picture_id');
        });

        Schema::dropIfExists('user_profile_picture');
        Schema::dropIfExists('profile_pictures');
        Schema::dropIfExists('pictures');

        Schema::create('pictures', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->text('filepath');
            $table->text('alt')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
