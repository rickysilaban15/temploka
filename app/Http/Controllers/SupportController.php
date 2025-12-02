<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Menampilkan halaman pusat bantuan
     */
    public function helpCenter()
    {
        $faqs = [
            [
                'category' => 'Akun & Registrasi',
                'questions' => [
                    [
                        'question' => 'Bagaimana cara membuat akun Temploka?',
                        'answer' => 'Klik tombol "Daftar" di pojok kanan atas, isi formulir pendaftaran dengan email dan password Anda, lalu verifikasi email Anda melalui link yang dikirim ke inbox.'
                    ],
                    [
                        'question' => 'Apakah ada biaya pendaftaran?',
                        'answer' => 'Tidak, pendaftaran akun Temploka sepenuhnya gratis. Anda hanya akan dikenakan biaya ketika memilih template premium atau upgrade ke plan berbayar.'
                    ]
                ]
            ],
            [
                'category' => 'Template & Penggunaan',
                'questions' => [
                    [
                        'question' => 'Bagaimana cara menggunakan template?',
                        'answer' => 'Pilih template yang diinginkan dari katalog, klik "Gunakan Template", lalu ikuti proses onboarding untuk menyesuaikan template dengan kebutuhan bisnis Anda.'
                    ],
                    [
                        'question' => 'Apakah template bisa dikustomisasi?',
                        'answer' => 'Ya, semua template dapat dikustomisasi sesuai kebutuhan bisnis Anda melalui editor drag-and-drop yang user-friendly.'
                    ]
                ]
            ],
            [
                'category' => 'Pembayaran & Invoice',
                'questions' => [
                    [
                        'question' => 'Metode pembayaran apa saja yang diterima?',
                        'answer' => 'Kami menerima transfer bank, kartu kredit/debit, e-wallet (OVO, Gopay, Dana), dan QRIS.'
                    ],
                    [
                        'question' => 'Bagaimana cara mengunduh invoice?',
                        'answer' => 'Invoice dapat diunduh dari dashboard Anda di bagian "Riwayat Transaksi" setelah pembayaran berhasil.'
                    ]
                ]
            ]
        ];

        return view('support.help-center', [
            'title' => 'Pusat Bantuan',
            'description' => 'Temukan solusi untuk masalah yang Anda hadapi dengan Temploka',
            'faqs' => $faqs
        ]);
    }

    /**
     * Menampilkan halaman dokumentasi
     */
    public function documentation()
    {
        $docs = [
            [
                'title' => 'Getting Started',
                'description' => 'Panduan memulai menggunakan Temploka',
                'icon' => '🚀',
                'sections' => [
                    'Pendaftaran Akun',
                    'Proses Onboarding', 
                    'Memilih Template Pertama'
                ]
            ],
            [
                'title' => 'Template Guide',
                'description' => 'Panduan penggunaan template',
                'icon' => '📄',
                'sections' => [
                    'Memilih Template',
                    'Kustomisasi Template',
                    'Publish Website'
                ]
            ],
            [
                'title' => 'API Documentation',
                'description' => 'Dokumentasi API untuk developer',
                'icon' => '🔧',
                'sections' => [
                    'Authentication',
                    'Template API',
                    'Integration API'
                ]
            ]
        ];

        return view('support.documentation', [
            'title' => 'Dokumentasi',
            'description' => 'Panduan lengkap penggunaan Temploka',
            'docs' => $docs
        ]);
    }

    /**
     * Menampilkan halaman tutorial
     */
    public function tutorials()
    {
        $tutorials = [
            [
                'title' => 'Membuat Website Toko Online',
                'description' => 'Panduan lengkap membuat website toko online dari nol',
                'duration' => '15 menit',
                'level' => 'Pemula',
                'video_url' => '#'
            ],
            [
                'title' => 'Kustomisasi Template Premium',
                'description' => 'Cara mengkustomisasi template sesuai brand bisnis Anda',
                'duration' => '25 menit', 
                'level' => 'Menengah',
                'video_url' => '#'
            ],
            [
                'title' => 'Integrasi dengan Payment Gateway',
                'description' => 'Tutorial integrasi pembayaran dengan berbagai metode',
                'duration' => '20 menit',
                'level' => 'Lanjutan',
                'video_url' => '#'
            ]
        ];

        return view('support.tutorials', [
            'title' => 'Tutorial',
            'description' => 'Video dan panduan langkah demi langkah',
            'tutorials' => $tutorials
        ]);
    }
}