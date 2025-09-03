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
        // Get existing users
        $users = User::all();
        
        if ($users->count() == 0) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        // Sample forum posts
        $posts = [
            [
                'title' => 'Selamat Datang ke Forum Komuniti Puncak Jalil!',
                'content' => 'Halo semua! Selamat datang ke forum komuniti kita yang baru. Di sini kita boleh berbincang tentang pelbagai topik berkaitan dengan kehidupan di Puncak Jalil.\n\nJom mulakan perbincangan yang sihat dan membantu antara jiran-jiran!',
                'category' => 'pengumuman',
                'is_pinned' => true
            ],
            [
                'title' => 'Aktiviti Gotong-royong Bulan September',
                'content' => 'Assalamualaikum dan salam sejahtera,\n\nKami ingin menganjurkan aktiviti gotong-royong pada:\n📅 Tarikh: 7 September 2025\n🕘 Masa: 7:00 AM - 10:00 AM\n📍 Lokasi: Taman permainan dan kawasan sekitar\n\nSila bawa:\n- Cangkul/penyapu\n- Sarung tangan\n- Air minuman\n\nTerima kasih!',
                'category' => 'aktiviti'
            ],
            [
                'title' => 'Adakah ada kedai roti yang sedap di sini?',
                'content' => 'Hi semua,\n\nSaya baru pindah ke Puncak Jalil. Boleh recommend kedai roti yang sedap tak? Yang dekat-dekat je.\n\nTerima kasih! 😊',
                'category' => 'soalan'
            ],
            [
                'title' => 'Lampu jalan rosak di Jalan Puncak 2',
                'content' => 'Nak report yang lampu jalan di Jalan Puncak 2 (depan rumah nombor 45) sudah rosak sejak 3 hari lepas.\n\nKawasan tu jadi gelap malam-malam, bahaya untuk keselamatan.\n\nDah report ke majlis perbandaran ke belum?',
                'category' => 'keselamatan'
            ],
            [
                'title' => 'Jual: Perabot rumah terpakai',
                'content' => '🏠 JUAL PERABOT RUMAH TERPAKAI 🏠\n\nHi semua, saya nak jual beberapa perabot sebab nak pindah:\n\n1. Sofa 3 tempat duduk - RM300\n2. Meja makan kayu + 4 kerusi - RM250\n3. Almari pakaian 3 pintu - RM400\n4. TV cabinet - RM150\n\nSemua dalam keadaan baik. WhatsApp saya 012-3456789 untuk gambar dan details.\n\nLokasi: Puncak Jalil, Seksyen 1',
                'category' => 'jual-beli'
            ],
            [
                'title' => 'Cadangan: Sistem CCTV untuk keselamatan',
                'content' => 'Salam semua,\n\nSaya nak cadangkan kita install sistem CCTV di beberapa kawasan strategic dalam taman kita untuk tingkatkan keselamatan.\n\nKawasan yang dicadangkan:\n- Pintu masuk utama\n- Taman permainan\n- Parking area\n\nApa pendapat korang? Boleh bincang dengan committee management tak?',
                'category' => 'cadangan'
            ]
        ];

        foreach ($posts as $postData) {
            $user = $users->random();
            
            $post = ForumPost::create([
                'user_id' => $user->id,
                'title' => $postData['title'],
                'content' => $postData['content'],
                'category' => $postData['category'],
                'views_count' => rand(5, 50),
                'is_pinned' => $postData['is_pinned'] ?? false,
                'is_locked' => false,
                'created_at' => now()->subDays(rand(0, 30)),
            ]);

            // Add some replies to posts
            if (rand(1, 3) <= 2) { // 66% chance of having replies
                $replyCount = rand(1, 5);
                
                for ($i = 0; $i < $replyCount; $i++) {
                    $replyUser = $users->random();
                    
                    $replies = [
                        'Terima kasih atas maklumat ini!',
                        'Setuju dengan cadangan ini. 👍',
                        'Saya berminat. Boleh WhatsApp details?',
                        'Good idea! Kita patut buat ini.',
                        'Thanks for sharing. Very helpful!',
                        'Saya sokong 100%! Jom buat.',
                        'Baik punya. Bila nak start?',
                        'Wah, murah ni. Masih ada ke?',
                        'Dah report ke authorities?',
                        'Saya join aktiviti ni!',
                        'Bagus punya cadangan.',
                        'Kena koordinat dengan management.',
                        'Thanks bro! Sangat membantu.',
                        'Area saya pun ada masalah sama.',
                        'Saya sokong initiative ni!'
                    ];
                    
                    ForumReply::create([
                        'post_id' => $post->id,
                        'user_id' => $replyUser->id,
                        'content' => $replies[array_rand($replies)],
                        'created_at' => $post->created_at->addMinutes(rand(30, 1440))
                    ]);
                }
            }
        }

        $this->command->info('Forum posts and replies seeded successfully!');
    }
}
