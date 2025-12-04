<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'body',
        'read_at',
        'attachment',
        'attachment_type',
        'attachment_name',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Scope messages exchanged between two users.
     */
    public function scopeBetweenUsers(Builder $query, int $userA, int $userB): Builder
    {
        return $query->where(function (Builder $builder) use ($userA, $userB) {
            $builder->where('sender_id', $userA)->where('receiver_id', $userB);
        })->orWhere(function (Builder $builder) use ($userA, $userB) {
            $builder->where('sender_id', $userB)->where('receiver_id', $userA);
        });
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}

