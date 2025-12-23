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
        // Check if the table already exists to prevent "Table already exists" error
        if (!Schema::hasTable('user_likes')) {
            Schema::create('user_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
                $table->foreignId('liked_user_id')->constrained('users')->onDelete('cascade'); 
                $table->enum('type', ['like', 'dislike'])->default('like'); 
                $table->timestamps();

                $table->unique(['user_id', 'liked_user_id']);
                
                $table->index('liked_user_id');
                $table->index('type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_likes');
    }
};