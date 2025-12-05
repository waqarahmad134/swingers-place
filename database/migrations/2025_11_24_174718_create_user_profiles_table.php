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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Profile Type & Category
            $table->enum('profile_type', ['normal', 'business'])->default('normal');
            $table->string('category')->nullable(); // couple, single_female, single_male, etc.
            
            // Preferences (Step 2)
            $table->json('preferences')->nullable(); // full_swap, soft_swap, etc.
            
            // Location (Step 3)
            $table->string('home_location')->nullable();
            $table->string('travel_location')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Languages (Step 4)
            $table->json('languages')->nullable();
            
            // Basic Information (Step 5)
            $table->date('date_of_birth')->nullable();
            $table->string('sexuality')->nullable();
            $table->string('relationship_status')->nullable();
            $table->string('experience')->nullable();
            $table->string('smoking')->nullable();
            $table->string('travel_options')->nullable();
            
            // Personal Details (Step 6)
            $table->integer('weight')->nullable(); // kg
            $table->integer('height')->nullable(); // cm
            $table->string('body_type')->nullable();
            $table->string('eye_color')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('tattoos')->nullable();
            $table->string('piercings')->nullable();
            $table->string('boob_size')->nullable();
            $table->string('dick_size')->nullable();
            
            // Location fields
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            
            // Photos (Step 7)
            $table->string('profile_photo')->nullable();
            $table->string('cover_photo')->nullable();
            $table->json('album_photos')->nullable();
            
            // Story (Step 8)
            $table->text('bio')->nullable();
            $table->text('looking_for')->nullable();
            $table->text('additional_notes')->nullable();
            
            // Couple Data (for couple category)
            $table->json('couple_data')->nullable();
            
            // Privacy Settings (Step 9)
            $table->boolean('profile_visible')->default(true);
            $table->boolean('allow_wall_posts')->default(true);
            $table->boolean('show_online_status')->default(true);
            $table->boolean('show_last_active')->default(true);
            $table->boolean('country_visibility')->default(false);
            $table->boolean('photo_filtering')->default(true);
            
            // Onboarding Status
            $table->boolean('onboarding_completed')->default(false);
            $table->integer('onboarding_step')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
