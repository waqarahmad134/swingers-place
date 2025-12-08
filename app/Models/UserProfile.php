<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'profile_type',
        'category',
        'preferences',
        'home_location',
        'country',
        'city',
        'travel_location',
        'latitude',
        'longitude',
        'languages',
        'date_of_birth',
        'sexuality',
        'relationship_status',
        'experience',
        'smoking',
        'travel_options',
        'weight',
        'height',
        'body_type',
        'eye_color',
        'hair_color',
        'tattoos',
        'piercings',
        'boob_size',
        'dick_size',
        'profile_photo',
        'album_photos',
        'bio',
        'looking_for',
        'additional_notes',
        'couple_data',
        'profile_visible',
        'allow_wall_posts',
        'show_online_status',
        'show_last_active',
        'country_visibility',
        'photo_filtering',
        'onboarding_completed',
        'onboarding_step',
    ];

    protected $casts = [
        'preferences' => 'array',
        'languages' => 'array',
        'album_photos' => 'array',
        'couple_data' => 'array',
        'date_of_birth' => 'date',
        'profile_visible' => 'boolean',
        'allow_wall_posts' => 'boolean',
        'show_online_status' => 'boolean',
        'show_last_active' => 'boolean',
        'country_visibility' => 'boolean',
        'photo_filtering' => 'boolean',
        'onboarding_completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
