<?php

namespace App\Models;

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
}
