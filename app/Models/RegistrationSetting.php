<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationSetting extends Model
{
    protected $fillable = [
        'open_registration',
        'email_verification',
        'admin_approval',
        'regions',
    ];

    protected $casts = [
        'open_registration' => 'boolean',
        'email_verification' => 'boolean',
        'admin_approval' => 'boolean',
        'regions' => 'array',
    ];

    /**
     * Get the registration settings (singleton pattern - always return the first/only record)
     */
    public static function getSettings(): self
    {
        $settings = self::first();
        
        if (!$settings) {
            // Create default settings if none exist
            $settings = self::create([
                'open_registration' => true,
                'email_verification' => false,
                'admin_approval' => false,
                'regions' => [
                    'north_america' => true,
                    'europe' => true,
                    'asia' => true,
                    'other_regions' => true,
                ],
            ]);
        }
        
        return $settings;
    }

    /**
     * Update registration settings
     */
    public static function updateSettings(array $data): self
    {
        $settings = self::getSettings();
        
        // Handle regions separately if provided
        if (isset($data['regions']) && is_array($data['regions'])) {
            $regions = $data['regions'];
            unset($data['regions']);
        } else {
            $regions = $settings->regions;
        }
        
        // Update boolean fields
        $settings->open_registration = $data['open_registration'] ?? $settings->open_registration;
        $settings->email_verification = $data['email_verification'] ?? $settings->email_verification;
        $settings->admin_approval = $data['admin_approval'] ?? $settings->admin_approval;
        $settings->regions = $regions;
        
        $settings->save();
        
        return $settings;
    }

    /**
     * Check if registration is open
     */
    public function isRegistrationOpen(): bool
    {
        return $this->open_registration;
    }
    
    /**
     * Get current status (derived from open_registration)
     */
    public function getCurrentStatusAttribute(): string
    {
        return $this->open_registration ? 'open' : 'closed';
    }

    /**
     * Check if email verification is required
     */
    public function requiresEmailVerification(): bool
    {
        return $this->email_verification;
    }

    /**
     * Check if admin approval is required
     */
    public function requiresAdminApproval(): bool
    {
        return $this->admin_approval;
    }
}
