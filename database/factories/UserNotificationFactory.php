<?php

namespace Database\Factories;

use App\Models\UserNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserNotificationFactory extends Factory
{
    protected $model = UserNotification::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'from_user_id' => null,
            'post_id' => null,
            'type' => $this->faker->randomElement(['post.liked', 'comment.reply', 'follow']),
            'title' => $this->faker->sentence(4),
            'message' => $this->faker->sentence(8),
            'is_read' => false,
            'created_at' => now()->subMinutes(rand(0, 10000)),
        ];
    }
}
