<?php

namespace App\Models;

use App\Models\Scopes\ActiveUserScope;
use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    protected $fillable = [
        'user_id',
        'comment_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(ReplyCommentLike::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new ActiveUserScope);
    }
}
