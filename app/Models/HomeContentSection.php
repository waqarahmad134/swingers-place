<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeContentSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'section_name',
        'content',
        'type',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get content by section key
     */
    public static function getContent(string $key, string $default = ''): string
    {
        $section = self::where('section_key', $key)
            ->where('is_active', true)
            ->first();
        
        return $section ? $section->content : $default;
    }

    /**
     * Update or create content by section key
     */
    public static function setContent(string $key, string $content, array $attributes = []): self
    {
        return self::updateOrCreate(
            ['section_key' => $key],
            array_merge([
                'content' => $content,
                'is_active' => true,
            ], $attributes)
        );
    }
}
