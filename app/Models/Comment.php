<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = [
        'user_id',
        'post_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function repost()
    {
        return $this->belongsTo(Repost::class);
    }

    public function isCommentedBy(User $user): bool
    {
        return $this->where('user_id', $user->id)->exists();
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function likeCount(): int
    {
        return $this->likes()->count();
    }

    public function addLike(User $user): void
    {
        if (! $this->isLikedBy($user)) {
            $this->likes()->create(['user_id' => $user->id]);
        }
    }

    public function removeLike(User $user): void
    {
        $this->likes()->where('user_id', $user->id)->delete();
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class, 'comment_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function isReply(): bool
    {
        return ! is_null($this->parent_id);
    }
}
