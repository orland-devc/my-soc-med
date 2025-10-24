<?php

namespace App\Models;

use App\Models\Scopes\ActiveUserScope;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $fillable = ['comment_id', 'user_id'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new ActiveUserScope);
    }
}
