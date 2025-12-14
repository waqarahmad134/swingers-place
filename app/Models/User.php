<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'gender',
        'is_admin',
        'is_editor',
        'is_active',
        'profile_image',
        'profile_type',
        'company',
        'website_url',
        'address',
        'business_address',
        'ssn',
        'email_verified_at',
        'last_seen_at',
        'scheduled_offline_at',
        'can_message',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'scheduled_offline_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_editor' => 'boolean',
            'is_active' => 'boolean',
            'can_message' => 'boolean',
        ];
    }

    /**
     * Get the user's profile.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Check if user is currently online.
     * User is considered online if last_seen_at is within the last 5 minutes.
     * Also checks if scheduled_offline_at has passed (admin-controlled auto-offline).
     */
    public function isOnline(): bool
    {
        // Check if admin scheduled an offline time and it has passed
        if ($this->scheduled_offline_at && $this->scheduled_offline_at->lte(now())) {
            return false;
        }

        if (!$this->last_seen_at) {
            return false;
        }

        return $this->last_seen_at->gt(now()->subMinutes(5));
    }

    /**
     * Update the last seen timestamp.
     */
    public function updateLastSeen(): void
    {
        $this->update(['last_seen_at' => now()]);
    }
}
