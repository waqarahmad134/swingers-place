<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlbumImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_id',
        'image_path',
    ];

    /**
     * Get the album that owns the image.
     */
    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
