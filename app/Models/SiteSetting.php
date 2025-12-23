<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_title',
        'site_description',
        'site_keywords',
        'site_icon',
        'site_favicon',
        'og_title',
        'og_description',
        'og_image',
        'og_site_name',
        'og_type',
        'og_url',
        'twitter_card_type',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'header_scripts',
        'footer_scripts',
        'custom_css',
        'custom_js',
    ];

    /**
     * Get the site settings (singleton pattern - always return the first/only record)
     */
    public static function getSettings(): self
    {
        $settings = self::first();
        
        if (!$settings) {
            // Create default settings if none exist
            $settings = self::create([
                'site_title' => config('app.name', 'My Website'),
                'site_description' => 'Welcome to our website',
                'site_keywords' => '',
                'og_site_name' => config('app.name', 'My Website'),
                'og_type' => 'website',
                'twitter_card_type' => 'summary_large_image',
            ]);
        }
        
        return $settings;
    }

    /**
     * Update site settings
     */
    public static function updateSettings(array $data): self
    {
        $settings = self::getSettings();
        $settings->update($data);
        return $settings;
    }
}
