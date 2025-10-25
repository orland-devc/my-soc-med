<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'from_user_id',
        'post_id',
        'comment_id',
        'follow_id',
        'type',
        'title',
        'message',
        'group_key',
        'priority',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public static function sendLikeNotification($post, $fromUser)
    {
        // Don't notify if the user liked their own post
        if ($post->user_id === $fromUser->id) {
            return;
        }

        // Avoid duplicate notifications (optional)
        $existing = self::where([
            ['user_id', '=', $post->user_id],
            ['from_user_id', '=', $fromUser->id],
            ['post_id', '=', $post->id],
            ['type', '=', 'like'],
        ])->first();

        if ($existing) {
            return;
        }

        // Create the notification
        self::create([
            'user_id'      => $post->user_id,     // receiver (post owner)
            'from_user_id' => $fromUser->id,      // sender (liker)
            'post_id'      => $post->id,
            'type'         => 'like',
            'message'      => "{$fromUser->name} liked your post.",
            'is_read'      => false,
        ]);
    }

    public static function sendCommentNotification($post, $fromUser, $commentText)
    {
        // Don’t notify if the commenter is the post owner
        if ($post->user_id === $fromUser->id) {
            return;
        }

        // Optional: Prevent multiple notifications for identical comments
        $existing = self::where([
            ['user_id', '=', $post->user_id],
            ['from_user_id', '=', $fromUser->id],
            ['post_id', '=', $post->id],
            ['type', '=', 'comment'],
        ])
        ->where('created_at', '>=', now()->subMinutes(1)) // avoid flooding
        ->first();

        if ($existing) {
            return;
        }

        // Truncate comment text for readability (optional)
        $excerpt = strlen($commentText) > 50
            ? substr($commentText, 0, 47) . '...'
            : $commentText;

        // Create the notification
        self::create([
            'user_id'      => $post->user_id,     // post owner (receiver)
            'from_user_id' => $fromUser->id,      // commenter
            'post_id'      => $post->id,
            'type'         => 'comment',
            'message'      => "{$fromUser->name} commented: “{$excerpt}”",
            'is_read'      => false,
        ]);
    }

}
