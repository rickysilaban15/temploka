<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class WebinarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Data webinar - SESUAIKAN DENGAN MIGRATION YANG BARU
        $webinars = [
            [
                'name' => 'Strategi Digital Marketing untuk UMKM 2025', // GUNAKAN 'name' BUKAN 'title'
                'slug' => 'strategi-digital-marketing-umkm-2025',
                'description' => 'Pelajari strategi digital marketing terkini yang efektif untuk meningkatkan penjualan UMKM Anda di tahun 2025.',
                'instructor_name' => 'Budi Santoso', // GUNAKAN 'instructor_name' BUKAN 'instructor'
                'instructor_title' => 'Digital Marketing Expert',
                'instructor_avatar' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=009689&color=fff',
                'image_url' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=800',
                'is_live' => true,
                'is_recorded' => false,
                'start_date' => $now->copy()->addHours(1),
                'end_date' => $now->copy()->addHours(3),
                'duration_minutes' => 120,
                'max_participants' => 100,
                'current_participants' => 47,
                'status' => 'live',
                'button_text' => 'Ikuti Live',
                'price' => 0,
                'is_free' => true,
                'category' => 'marketing',
                'level' => 'intermediate',
                'order' => 1
            ],
            [
                'name' => 'Memaksimalkan Penjualan dengan E-Commerce',
                'slug' => 'memaksimalkan-penjualan-ecommerce',
                'description' => 'Workshop praktis tentang cara meningkatkan konversi dan penjualan di platform e-commerce Anda.',
                'instructor_name' => 'Siti Nurhaliza',
                'instructor_title' => 'E-Commerce Specialist',
                'instructor_avatar' => 'https://ui-avatars.com/api/?name=Siti+Nurhaliza&background=009689&color=fff',
                'image_url' => 'https://images.unsplash.com/photo-1556742502-ec7c0e9f34b1?w=800',
                'is_live' => false,
                'is_recorded' => false,
                'start_date' => $now->copy()->addDays(2)->setTime(14, 0),
                'end_date' => $now->copy()->addDays(2)->setTime(16, 0),
                'duration_minutes' => 120,
                'max_participants' => 150,
                'current_participants' => 0,
                'status' => 'upcoming',
                'button_text' => 'Daftar Sekarang',
                'price' => 0,
                'is_free' => true,
                'category' => 'ecommerce',
                'level' => 'beginner',
                'order' => 2
            ],
            // ... webinar lainnya dengan struktur yang sama
        ];

        // Hanya insert data jika tabel ada
        if (Schema::hasTable('webinars')) {
            foreach ($webinars as $webinar) {
                DB::table('webinars')->updateOrInsert(
                    ['slug' => $webinar['slug']],
                    array_merge($webinar, [
                        'created_at' => now(),
                        'updated_at' => now()
                    ])
                );
            }
        }

        $this->command->info('Webinars seeded successfully!');
    }
}