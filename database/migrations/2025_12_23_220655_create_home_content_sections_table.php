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
        Schema::create('home_content_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique(); // e.g., 'hero_title', 'hero_subtitle', 'member_card_1_title'
            $table->string('section_name'); // Human readable name
            $table->text('content')->nullable(); // The actual content/text
            $table->string('type')->default('text'); // text, html, image_url
            $table->integer('order')->default(0); // For ordering sections
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('section_key');
            $table->index('is_active');
        });

        // Insert default content for all home page sections
        $defaultSections = [
            // Hero Section
            ['section_key' => 'hero_title', 'section_name' => 'Hero Title', 'content' => 'Dating for', 'type' => 'text', 'order' => 1],
            ['section_key' => 'hero_title_highlight', 'section_name' => 'Hero Title Highlight', 'content' => 'Naughty', 'type' => 'text', 'order' => 2],
            ['section_key' => 'hero_title_ending', 'section_name' => 'Hero Title Ending', 'content' => 'Adults', 'type' => 'text', 'order' => 3],
            ['section_key' => 'hero_subtitle', 'section_name' => 'Hero Subtitle', 'content' => 'Meet real people nearby who are ready for genuine connections', 'type' => 'text', 'order' => 4],
            ['section_key' => 'hero_cta_text', 'section_name' => 'Hero CTA Text', 'content' => 'Free Sign Up', 'type' => 'text', 'order' => 5],
            
            // Search Section
            ['section_key' => 'search_title', 'section_name' => 'Search Section Title', 'content' => 'Find the Best Option for You', 'type' => 'text', 'order' => 10],
            ['section_key' => 'search_description', 'section_name' => 'Search Section Description', 'content' => 'Search through categories to discover exactly what you need', 'type' => 'text', 'order' => 11],
            
            // Member Section
            ['section_key' => 'member_title', 'section_name' => 'Member Section Title', 'content' => 'Want to Become a Member?', 'type' => 'text', 'order' => 20],
            ['section_key' => 'member_description', 'section_name' => 'Member Section Description', 'content' => 'Why pay to use a dating app if you could use our platform? Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.', 'type' => 'text', 'order' => 21],
            ['section_key' => 'member_card_1_title', 'section_name' => 'Member Card 1 Title', 'content' => '100% for FREE', 'type' => 'text', 'order' => 22],
            ['section_key' => 'member_card_1_description', 'section_name' => 'Member Card 1 Description', 'content' => 'Create your profile and start connecting with singles in your area without paying a dime.', 'type' => 'text', 'order' => 23],
            ['section_key' => 'member_card_1_button', 'section_name' => 'Member Card 1 Button', 'content' => 'Learn More', 'type' => 'text', 'order' => 24],
            ['section_key' => 'member_card_2_title', 'section_name' => 'Member Card 2 Title', 'content' => 'Matching compatible partner', 'type' => 'text', 'order' => 25],
            ['section_key' => 'member_card_2_description', 'section_name' => 'Member Card 2 Description', 'content' => 'Our advanced algorithm helps you find the perfect match based on your interests and preferences.', 'type' => 'text', 'order' => 26],
            ['section_key' => 'member_card_2_button', 'section_name' => 'Member Card 2 Button', 'content' => 'Learn More', 'type' => 'text', 'order' => 27],
            ['section_key' => 'member_card_3_title', 'section_name' => 'Member Card 3 Title', 'content' => 'Share experiences', 'type' => 'text', 'order' => 28],
            ['section_key' => 'member_card_3_description', 'section_name' => 'Member Card 3 Description', 'content' => 'Connect with people who share your passions and create meaningful memories together.', 'type' => 'text', 'order' => 29],
            ['section_key' => 'member_card_3_button', 'section_name' => 'Member Card 3 Button', 'content' => 'Learn More', 'type' => 'text', 'order' => 30],
            
            // Journey Section
            ['section_key' => 'journey_title', 'section_name' => 'Journey Section Title', 'content' => 'Your Journey Starts Here', 'type' => 'text', 'order' => 40],
            ['section_key' => 'journey_step_1_title', 'section_name' => 'Journey Step 1 Title', 'content' => 'Sign Up For Free', 'type' => 'text', 'order' => 41],
            ['section_key' => 'journey_step_1_description', 'section_name' => 'Journey Step 1 Description', 'content' => 'Create your account in seconds and start your journey to finding love. It\'s completely free to join.', 'type' => 'text', 'order' => 42],
            ['section_key' => 'journey_step_2_title', 'section_name' => 'Journey Step 2 Title', 'content' => 'Get Matches', 'type' => 'text', 'order' => 43],
            ['section_key' => 'journey_step_2_description', 'section_name' => 'Journey Step 2 Description', 'content' => 'Our smart matching algorithm will connect you with compatible singles who share your interests and values.', 'type' => 'text', 'order' => 44],
            ['section_key' => 'journey_step_3_title', 'section_name' => 'Journey Step 3 Title', 'content' => 'Start Dating', 'type' => 'text', 'order' => 45],
            ['section_key' => 'journey_step_3_description', 'section_name' => 'Journey Step 3 Description', 'content' => 'Connect with your matches, start conversations, and begin your journey to finding meaningful relationships.', 'type' => 'text', 'order' => 46],
            ['section_key' => 'journey_step_4_title', 'section_name' => 'Journey Step 4 Title', 'content' => 'Find Love', 'type' => 'text', 'order' => 47],
            ['section_key' => 'journey_step_4_description', 'section_name' => 'Journey Step 4 Description', 'content' => 'Build lasting connections and discover the relationship you\'ve been searching for with someone special.', 'type' => 'text', 'order' => 48],
            
            // Date Section
            ['section_key' => 'date_title', 'section_name' => 'Date Section Title', 'content' => 'It all starts with a Date', 'type' => 'text', 'order' => 50],
            ['section_key' => 'date_description', 'section_name' => 'Date Section Description', 'content' => 'You find us, finally, and you are already in love. More than 5,000,000 around the world already shared the same experience and uses our system. Joining us today just got easier!', 'type' => 'text', 'order' => 51],
            ['section_key' => 'date_button', 'section_name' => 'Date Section Button', 'content' => 'Join Us FREE', 'type' => 'text', 'order' => 52],
            ['section_key' => 'date_stat_1_number', 'section_name' => 'Date Stat 1 Number', 'content' => '5 MILLION', 'type' => 'text', 'order' => 53],
            ['section_key' => 'date_stat_1_label', 'section_name' => 'Date Stat 1 Label', 'content' => 'Users in total', 'type' => 'text', 'order' => 54],
            ['section_key' => 'date_stat_2_number', 'section_name' => 'Date Stat 2 Number', 'content' => '947', 'type' => 'text', 'order' => 55],
            ['section_key' => 'date_stat_2_label', 'section_name' => 'Date Stat 2 Label', 'content' => 'Verified online', 'type' => 'text', 'order' => 56],
            ['section_key' => 'date_stat_3_number', 'section_name' => 'Date Stat 3 Number', 'content' => '530', 'type' => 'text', 'order' => 57],
            ['section_key' => 'date_stat_3_label', 'section_name' => 'Date Stat 3 Label', 'content' => 'Female users', 'type' => 'text', 'order' => 58],
            ['section_key' => 'date_stat_4_number', 'section_name' => 'Date Stat 4 Number', 'content' => '417', 'type' => 'text', 'order' => 59],
            ['section_key' => 'date_stat_4_label', 'section_name' => 'Date Stat 4 Label', 'content' => 'Male users', 'type' => 'text', 'order' => 60],
            
            // Grid Section
            ['section_key' => 'grid_card_1_title', 'section_name' => 'Grid Card 1 Title', 'content' => 'Swingers Partner Program', 'type' => 'text', 'order' => 70],
            ['section_key' => 'grid_card_1_description', 'section_name' => 'Grid Card 1 Description', 'content' => 'It\'s now easier than ever for you to make money... and grow your business with Swingers - all in one.', 'type' => 'text', 'order' => 71],
            ['section_key' => 'grid_card_2_title', 'section_name' => 'Grid Card 2 Title', 'content' => 'Dealing With Love', 'type' => 'text', 'order' => 72],
            ['section_key' => 'grid_card_2_description', 'section_name' => 'Grid Card 2 Description', 'content' => 'It\'s now easier than ever for you to make money...', 'type' => 'text', 'order' => 73],
            ['section_key' => 'grid_card_3_title', 'section_name' => 'Grid Card 3 Title', 'content' => 'Dealing With Love', 'type' => 'text', 'order' => 74],
            ['section_key' => 'grid_card_3_description', 'section_name' => 'Grid Card 3 Description', 'content' => 'It\'s now easier than ever for you to make money...', 'type' => 'text', 'order' => 75],
            ['section_key' => 'grid_card_4_title', 'section_name' => 'Grid Card 4 Title', 'content' => 'Dealing With loneliness', 'type' => 'text', 'order' => 76],
            ['section_key' => 'grid_card_4_description', 'section_name' => 'Grid Card 4 Description', 'content' => 'It\'s now easier than ever for you to make money...', 'type' => 'text', 'order' => 77],
            ['section_key' => 'grid_card_5_title', 'section_name' => 'Grid Card 5 Title', 'content' => 'Dealing With loneliness', 'type' => 'text', 'order' => 78],
            ['section_key' => 'grid_card_5_description', 'section_name' => 'Grid Card 5 Description', 'content' => 'It\'s now easier than ever for you to make money...', 'type' => 'text', 'order' => 79],
            ['section_key' => 'grid_card_6_title', 'section_name' => 'Grid Card 6 Title', 'content' => 'Dealing With loneliness', 'type' => 'text', 'order' => 80],
            ['section_key' => 'grid_card_6_description', 'section_name' => 'Grid Card 6 Description', 'content' => 'It\'s now easier than ever for you to make money...', 'type' => 'text', 'order' => 81],
            ['section_key' => 'grid_card_7_title', 'section_name' => 'Grid Card 7 Title', 'content' => 'Swingers Partner Program', 'type' => 'text', 'order' => 82],
            ['section_key' => 'grid_card_7_description', 'section_name' => 'Grid Card 7 Description', 'content' => 'It\'s now easier than ever for you to make money... and grow your business with Swingers - all in one.', 'type' => 'text', 'order' => 83],
            ['section_key' => 'grid_card_8_title', 'section_name' => 'Grid Card 8 Title', 'content' => 'Super Sexperience..', 'type' => 'text', 'order' => 84],
            ['section_key' => 'grid_card_8_description', 'section_name' => 'Grid Card 8 Description', 'content' => 'It\'s now easier than ever for you to make money…', 'type' => 'text', 'order' => 85],
            ['section_key' => 'grid_card_9_title', 'section_name' => 'Grid Card 9 Title', 'content' => 'Travel Journey', 'type' => 'text', 'order' => 86],
            ['section_key' => 'grid_card_9_description', 'section_name' => 'Grid Card 9 Description', 'content' => 'It\'s now easier than ever for you to make money...', 'type' => 'text', 'order' => 87],
            ['section_key' => 'grid_card_10_title', 'section_name' => 'Grid Card 10 Title', 'content' => 'Travel Journey', 'type' => 'text', 'order' => 88],
            ['section_key' => 'grid_card_10_description', 'section_name' => 'Grid Card 10 Description', 'content' => 'It\'s now easier than ever for you to make money...', 'type' => 'text', 'order' => 89],
            ['section_key' => 'grid_card_11_title', 'section_name' => 'Grid Card 11 Title', 'content' => 'Super Fitness', 'type' => 'text', 'order' => 90],
            ['section_key' => 'grid_card_11_description', 'section_name' => 'Grid Card 11 Description', 'content' => 'It\'s now easier than ever for you to make money...', 'type' => 'text', 'order' => 91],
            ['section_key' => 'grid_footer_title', 'section_name' => 'Grid Footer Title', 'content' => 'Start Your Love Story Today', 'type' => 'text', 'order' => 92],
            ['section_key' => 'grid_footer_description', 'section_name' => 'Grid Footer Description', 'content' => 'Over 2,000 success stories this month', 'type' => 'text', 'order' => 93],
        ];

        foreach ($defaultSections as $section) {
            DB::table('home_content_sections')->insert(array_merge($section, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_content_sections');
    }
};
