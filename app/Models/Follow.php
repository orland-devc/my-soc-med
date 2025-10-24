<?php

namespace App\Models;

use App\Models\Scopes\ActiveUserScope;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = [
        'user_id',
        'following_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new ActiveUserScope);
    }
}
