<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'password',
        'is_private',
        'image_count',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'image_count' => 'integer',
    ];

    /**
     * Get the user that owns the album.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the images for the album.
     */
    public function images()
    {
        return $this->hasMany(AlbumImage::class);
    }

    /**
     * Set the password and update is_private flag.
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
            $this->attributes['is_private'] = true;
        } else {
            $this->attributes['password'] = null;
            $this->attributes['is_private'] = false;
        }
    }

    /**
     * Check if the provided password is correct.
     */
    public function checkPassword($password)
    {
        if (!$this->is_private) {
            return true; // Public album, no password needed
        }
        
        return Hash::check($password, $this->password);
    }
}
