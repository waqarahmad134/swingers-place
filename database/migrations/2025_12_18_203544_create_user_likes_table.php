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
        Schema::create('user_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The user who likes/dislikes
            $table->foreignId('liked_user_id')->constrained('users')->onDelete('cascade'); // The user being liked/disliked
            $table->enum('type', ['like', 'dislike'])->default('like'); // like or dislike
            $table->timestamps();

            // Ensure a user can only have one like/dislike per other user
            $table->unique(['user_id', 'liked_user_id']);
            
            // Index for faster queries
            $table->index('liked_user_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_likes');
    }
};
