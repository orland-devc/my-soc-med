<?php

namespace App\Models;

use App\Models\Scopes\ActiveUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'comment_id',
        'reply_comment_id',
        'chat_id',
        'file_name',
        'file_location',
        'file_size',
        'visibility',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function replyComment()
    {
        return $this->belongsTo(CommentReply::class);
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new ActiveUserScope);
    }
}
