<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'uploader');
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
}
