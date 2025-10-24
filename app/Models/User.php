<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::substr($name, 0, 1))
            ->implode('');
    }

    public function messages()
    {
        return $this->hasMany(Inbox::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function recentComment()
    {
        return $this->comments()->latest()->first();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function hasLiked(Post $post): bool
    {
        return $this->likes()->where('post_id', $post->id)->exists();
    }

    public function reposts()
    {
        return $this->hasMany(Repost::class);
    }

    public function savedPosts()
    {
        return $this->hasMany(SavedPost::class);
    }

    public function hasReposted(Post $post): bool
    {
        return $this->reposts()->where('post_id', $post->id)->exists();
    }

    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function hasLikedComment(Comment $comment): bool
    {
        return $this->commentLikes()->where('comment_id', $comment->id)->exists();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    protected static function booted()
    {
        static::deleting(function ($user) {
            $user->posts()->delete();
        });
    }
}
