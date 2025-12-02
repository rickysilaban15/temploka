<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data integrasi platform
        $integrations = [
            [
                'name' => 'WhatsApp Business',
                'slug' => 'whatsapp-business',
                'description' => 'Hubungkan dengan WhatsApp Business API untuk komunikasi pelanggan',
                'status' => 'Aktif',
                'status_text' => 'Terhubung dengan akun bisnis Anda',
                'icon' => 'fab fa-whatsapp',
                'logo_color' => '#25D366',
                'category' => 'messaging',
                'is_active' => true,
                'order' => 1
            ],
            [
                'name' => 'Tokopedia',
                'slug' => 'tokopedia',
                'description' => 'Sinkronkan produk dan pesanan dari Tokopedia',
                'status' => 'Tidak Aktif',
                'status_text' => 'Belum terhubung dengan Tokopedia',
                'icon' => 'fas fa-shopping-bag',
                'logo_color' => '#42B549',
                'category' => 'marketplace',
                'is_active' => false,
                'order' => 2
            ],
            [
                'name' => 'Shopee',
                'slug' => 'shopee',
                'description' => 'Integrasi dengan toko Shopee Anda',
                'status' => 'Tidak Aktif',
                'status_text' => 'Belum terhubung dengan Shopee',
                'icon' => 'fas fa-store',
                'logo_color' => '#EE4D2D',
                'category' => 'marketplace',
                'is_active' => false,
                'order' => 3
            ],
            [
                'name' => 'Instagram',
                'slug' => 'instagram',
                'description' => 'Kelola produk dan pesan dari Instagram Shopping',
                'status' => 'Aktif',
                'status_text' => 'Terhubung dengan akun Instagram Anda',
                'icon' => 'fab fa-instagram',
                'logo_color' => '#E4405F',
                'category' => 'social_media',
                'is_active' => true,
                'order' => 4
            ],
            [
                'name' => 'Midtrans',
                'slug' => 'midtrans',
                'description' => 'Payment gateway untuk menerima pembayaran online',
                'status' => 'Tidak Aktif',
                'status_text' => 'Belum terhubung dengan Midtrans',
                'icon' => 'fas fa-credit-card',
                'logo_color' => '#00B4D8',
                'category' => 'payment',
                'is_active' => false,
                'order' => 5
            ],
            [
                'name' => 'Xendit',
                'slug' => 'xendit',
                'description' => 'Platform pembayaran digital Indonesia',
                'status' => 'Tidak Aktif',
                'status_text' => 'Belum terhubung dengan Xendit',
                'icon' => 'fas fa-wallet',
                'logo_color' => '#1746F0',
                'category' => 'payment',
                'is_active' => false,
                'order' => 6
            ],
            [
                'name' => 'JNE',
                'slug' => 'jne',
                'description' => 'Integrasi pengiriman dengan JNE',
                'status' => 'Tidak Aktif',
                'status_text' => 'Belum terhubung dengan JNE',
                'icon' => 'fas fa-truck',
                'logo_color' => '#E31E24',
                'category' => 'shipping',
                'is_active' => false,
                'order' => 7
            ],
            [
                'name' => 'SiCepat',
                'slug' => 'sicepat',
                'description' => 'Layanan pengiriman ekspres Indonesia',
                'status' => 'Tidak Aktif',
                'status_text' => 'Belum terhubung dengan SiCepat',
                'icon' => 'fas fa-shipping-fast',
                'logo_color' => '#FFB800',
                'category' => 'shipping',
                'is_active' => false,
                'order' => 8
            ],
            [
                'name' => 'Google Analytics',
                'slug' => 'google-analytics',
                'description' => 'Analitik website dan perilaku pelanggan',
                'status' => 'Aktif',
                'status_text' => 'Terhubung dan melacak data',
                'icon' => 'fab fa-google',
                'logo_color' => '#F9AB00',
                'category' => 'analytics',
                'is_active' => true,
                'order' => 9
            ],
            [
                'name' => 'Meta Business',
                'slug' => 'meta-business',
                'description' => 'Integrasi dengan Facebook dan Instagram Ads',
                'status' => 'Tidak Aktif',
                'status_text' => 'Belum terhubung dengan Meta Business',
                'icon' => 'fab fa-facebook',
                'logo_color' => '#1877F2',
                'category' => 'marketing',
                'is_active' => false,
                'order' => 10
            ],
            [
                'name' => 'Google Drive',
                'slug' => 'google-drive',
                'description' => 'Backup otomatis data ke Google Drive',
                'status' => 'Tidak Aktif',
                'status_text' => 'Belum terhubung dengan Google Drive',
                'icon' => 'fab fa-google-drive',
                'logo_color' => '#4285F4',
                'category' => 'storage',
                'is_active' => false,
                'order' => 11
            ],
            [
                'name' => 'Mailchimp',
                'slug' => 'mailchimp',
                'description' => 'Email marketing dan automasi kampanye',
                'status' => 'Tidak Aktif',
                'status_text' => 'Belum terhubung dengan Mailchimp',
                'icon' => 'fas fa-envelope',
                'logo_color' => '#FFE01B',
                'category' => 'marketing',
                'is_active' => false,
                'order' => 12
            ]
        ];

        // Insert data integrations (HAPUS bagian create table)
        foreach ($integrations as $integration) {
            DB::table('integrations')->insert(array_merge($integration, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        $this->command->info('Integrations seeded successfully!');
    }
}