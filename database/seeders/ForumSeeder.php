<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumPost;
use App\Models\ForumReply;
use App\Models\User;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        foreach ($users->random(2) as $user) {
            $post = ForumPost::create([
                'user_id' => $user->id,
                'title' => 'Perbincangan Komuniti',
                'content' => 'Mari bincang isu kawasan perumahan.',
            ]);
            ForumReply::create([
                'post_id' => $post->id,
                'user_id' => $users->random()->id,
                'content' => 'Saya setuju dengan topik ini!',
            ]);
        }
    }
}
