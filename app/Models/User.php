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
        'created_by',
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
     * Get the user who created this user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all users created by this user.
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Get all albums created by this user.
     */
    public function albums()
    {
        return $this->hasMany(Album::class);
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

    /**
     * Get likes given by this user.
     */
    public function likesGiven()
    {
        return $this->hasMany(UserLike::class, 'user_id');
    }

    /**
     * Get likes received by this user.
     */
    public function likesReceived()
    {
        return $this->hasMany(UserLike::class, 'liked_user_id');
    }

    /**
     * Get the count of likes received.
     */
    public function getLikesCountAttribute(): int
    {
        return $this->likesReceived()->where('type', 'like')->count();
    }

    /**
     * Check if current user has liked this user.
     */
    public function isLikedBy(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }
        return $this->likesReceived()->where('user_id', $userId)->where('type', 'like')->exists();
    }

    /**
     * Check if current user has disliked this user.
     */
    public function isDislikedBy(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }
        return $this->likesReceived()->where('user_id', $userId)->where('type', 'dislike')->exists();
    }
}
