<?php

namespace App\Models;

use App\Models\Scopes\ActiveUserScope;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //
    protected $fillable = [
        'user_id',
        'post_id',
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

    protected static function booted()
    {
        static::addGlobalScope(new ActiveUserScope);
    }
}
