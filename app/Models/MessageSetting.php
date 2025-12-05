<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageSetting extends Model
{
    protected $fillable = [
        'global_messaging_enabled',
    ];

    protected $casts = [
        'global_messaging_enabled' => 'boolean',
    ];

    /**
     * Get the message settings (singleton pattern - always return the first/only record)
     */
    public static function getSettings(): self
    {
        $settings = self::first();
        
        if (!$settings) {
            // Create default settings if none exist
            $settings = self::create([
                'global_messaging_enabled' => true,
            ]);
        }
        
        return $settings;
    }

    /**
     * Update message settings
     */
    public static function updateSettings(array $data): self
    {
        $settings = self::getSettings();
        $settings->update($data);
        return $settings;
    }
}
