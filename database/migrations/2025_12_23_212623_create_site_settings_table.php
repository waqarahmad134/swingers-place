<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            // Basic Site Info
            $table->string('site_title')->nullable();
            $table->text('site_description')->nullable();
            $table->text('site_keywords')->nullable();
            $table->string('site_icon')->nullable(); // Path to site icon
            $table->string('site_favicon')->nullable(); // Path to favicon
            
            // Open Graph Meta Tags
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable(); // Path to OG image
            $table->string('og_site_name')->nullable();
            $table->string('og_type')->default('website');
            $table->string('og_url')->nullable();
            
            // Twitter Card Meta Tags
            $table->string('twitter_card_type')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable(); // Path to Twitter image
            
            // Scripts
            $table->text('header_scripts')->nullable(); // Scripts to add in <head> (GSC, analytics, etc.)
            $table->text('footer_scripts')->nullable(); // Scripts to add before </body>
            $table->text('custom_css')->nullable(); // Custom CSS
            $table->text('custom_js')->nullable(); // Custom JavaScript
            
            $table->timestamps();
        });

        // Insert default settings
        DB::table('site_settings')->insert([
            'site_title' => config('app.name', 'My Website'),
            'site_description' => 'Welcome to our website',
            'site_keywords' => '',
            'og_site_name' => config('app.name', 'My Website'),
            'og_type' => 'website',
            'twitter_card_type' => 'summary_large_image',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
