<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'uploader',
        'caption',
        'description',
        'privacy',
        'archived',
        'comments',
        'is_pinned',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uploader');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function reposts()
    {
        return $this->hasMany(Repost::class);
    }

    public function isRepostedBy(User $user): bool
    {
        return $this->reposts()->where('user_id', $user->id)->exists();
    }

    public function recentComment()
    {
        $userId = Auth::id();

        return $this->comments()->where('user_id', $userId)->latest()->first();
    }

    public function archived()
    {
        return $this->where('archived', true);
    }
}
