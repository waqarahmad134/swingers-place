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
        // Step 1: Change enum to VARCHAR temporarily to allow any value
        DB::statement("ALTER TABLE users MODIFY COLUMN profile_type VARCHAR(20) DEFAULT 'normal'");
        
        // Step 2: Update existing values
        DB::statement("UPDATE users SET profile_type = CASE 
            WHEN profile_type = 'public' THEN 'normal'
            WHEN profile_type = 'private' THEN 'business'
            WHEN profile_type = 'normal' THEN 'normal'
            WHEN profile_type = 'business' THEN 'business'
            ELSE 'normal'
        END");
        
        // Step 3: Change back to enum with new values
        DB::statement("ALTER TABLE users MODIFY COLUMN profile_type ENUM('normal', 'business') DEFAULT 'normal'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Change enum to VARCHAR temporarily
        DB::statement("ALTER TABLE users MODIFY COLUMN profile_type VARCHAR(20) DEFAULT 'private'");
        
        // Step 2: Convert back to old enum values
        DB::statement("UPDATE users SET profile_type = CASE 
            WHEN profile_type = 'normal' THEN 'public'
            WHEN profile_type = 'business' THEN 'private'
            ELSE 'private'
        END");
        
        // Step 3: Change back to original enum
        DB::statement("ALTER TABLE users MODIFY COLUMN profile_type ENUM('public', 'private') DEFAULT 'private'");
    }
};
