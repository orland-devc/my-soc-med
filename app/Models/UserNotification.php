<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'is_viewed',
        'link',
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
        if ($post->user_id == $fromUser->id) {
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
            'user_id' => $post->user_id,     // receiver (post owner)
            'from_user_id' => $fromUser->id,      // sender (liker)
            'post_id' => $post->id,
            'type' => 'like',
            'message' => "{$fromUser->name} liked your post.",
            'is_read' => false,
            'link' => route('posts.show', ['id' => $post->id]),
        ]);
    }

    public static function sendCommentNotification($post, $fromUser, $commentText)
    {
        // Don’t notify if the commenter is the post owner
        if ($post->user_id == $fromUser->id) {
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
            ? substr($commentText, 0, 47).'...'
            : $commentText;

        // Create the notification
        self::create([
            'user_id' => $post->user_id,     // post owner (receiver)
            'from_user_id' => $fromUser->id,      // commenter
            'post_id' => $post->id,
            'type' => 'comment',
            'message' => "{$fromUser->name} commented: “{$excerpt}”",
            'is_read' => false,
            'link' => route('posts.show', ['id' => $post->id]),
        ]);
    }

    public static function sendRepostNotification($post, $fromUser)
    {
        // Don’t notify if the user reposted their own post
        if ($post->user_id == $fromUser->id) {
            return;
        }

        // Optional: prevent duplicate repost notifications
        $existing = self::where([
            ['user_id', '=', $post->user_id],
            ['from_user_id', '=', $fromUser->id],
            ['post_id', '=', $post->id],
            ['type', '=', 'repost'],
        ])->first();

        if ($existing) {
            return;
        }

        // Create the notification
        self::create([
            'user_id' => $post->user_id,     // post owner (receiver)
            'from_user_id' => $fromUser->id,      // reposter
            'post_id' => $post->id,
            'type' => 'repost',
            'message' => "{$fromUser->name} reposted your post.",
            'is_read' => false,
            'link' => route('posts.show', ['id' => $post->id]),
        ]);
    }

    public static function sendFollowNotification($fromUser, $toUser)
    {
        // Don’t notify if the user followed themselves
        if ($fromUser->id == $toUser->id) {
            return;
        }

        // Optional: prevent duplicate follow notifications
        $existing = self::where([
            ['user_id', '=', $toUser->id],
            ['from_user_id', '=', $fromUser->id],
            ['type', '=', 'follow'],
        ])->first();

        if ($existing) {
            return;
        }

        // Create the notification
        self::create([
            'user_id' => $toUser->id,     // receiver (followed user)
            'from_user_id' => $fromUser->id,   // sender (follower)
            'type' => 'follow',
            'message' => "{$fromUser->name} started following you.",
            'is_read' => false,
            'link' => route('user.show', ['id' => $fromUser->id]),
        ]);
    }
}
