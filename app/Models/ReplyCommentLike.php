<?php

namespace App\Models;

use App\Models\Scopes\ActiveUserScope;
use Illuminate\Database\Eloquent\Model;

class ReplyCommentLike extends Model
{
    protected $fillable = [
        'comment_reply_id',
        'user_id',
    ];

    public function replyComment()
    {
        return $this->belongsTo(CommentReply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedByCreator()
    {
        return $this->likes()->where('user_id', $this->post->user_id)->exists();
    }

    protected static function booted()
    {
        static::addGlobalScope(new ActiveUserScope);
    }
}
