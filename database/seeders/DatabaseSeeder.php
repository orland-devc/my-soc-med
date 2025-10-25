<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserNotification;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Orland Benniedict',
        //     'email' => 'orlandsayson30@gmail.com',
        //     'password' => bcrypt('orlandsayson30'),
        // ]);

        // Post::factory(10)->create();

        $user = User::create([
            'name' => 'Orland Benniedict',
            'email' => 'orlandsayson30@gmail.com',
            'password' => bcrypt('orlandsayson30'),
            'profile_photo_path' => 'images/uploads/AirBrush_20230924092316.jpg',
            'cover_path' => 'images/cover/1745508709861.png',
        ]);

        $this->command->info("Account {$user->email} created successfully.");

        $user1 = User::create([
            'name' => 'Levi D. Marbella',
            'email' => 'levi@gmail.com',
            'password' => bcrypt('orlandsayson30'),
            'profile_photo_path' => 'images/uploads/image.png',
            'cover_path' => 'images/cover/ghbili.jpg',
        ]);

        $this->command->info("Account {$user1->email} created successfully.");

        $user2 = User::create([
            'name' => 'Daniel John Padilla',
            'email' => 'daniel@gmail.com',
            'password' => bcrypt('orlandsayson30'),
            'profile_photo_path' => 'images/uploads/unnamed.jpg',
        ]);

        $this->command->info("Account {$user2->email} created successfully.");

        // $post = Post::create([
        //     'user_id' => 2,
        //     'caption' => 'My love🤍',
        // ]);

        // $this->command->info("Post by {$user1->name} added.");

        // $attachment = Attachment::create([
        //     'user_id' => 2,
        //     'post_id' => 1,
        //     'file_name' => 'IMG2024020208474613.jpg',
        //     'file_location' => 'images/posts/IMG2024020208474613.jpg',
        //     'file_size' => '77824',
        // ]);

        // $this->command->info("Attachment added for {$user1->name}'s post.");

        // $attachment = Attachment::create([
        //     'user_id' => '2',
        //     'post_id' => '2',
        //     'file_name' => 'me-anime.jpg',
        //     'file_location' => '\images\posts\me-anime.jpg',
        // ]);

        // $this->command->info("Product Category {$attachment->file_name} created successfully");

        UserNotification::factory()->count(20)->create(['user_id' => 2, 'post_id' => 1]);
        $this->command->info("200 Notifications created");

    }
}
