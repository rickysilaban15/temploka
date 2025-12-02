<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * Menampilkan halaman fitur
     */
    public function index()
    {
        $features = [
            [
                'title' => 'Template Profesional',
                'description' => '100+ template siap pakai untuk berbagai jenis bisnis',
                'icon' => '🎨',
                'details' => [
                    'Template responsive dan modern',
                    'Kustomisasi mudah dengan drag & drop',
                    'Optimized untuk SEO'
                ]
            ],
            [
                'title' => 'Editor Drag & Drop',
                'description' => 'Buat website tanpa coding dengan editor visual',
                'icon' => '🖱️',
                'details' => [
                    'Interface yang intuitif',
                    'Real-time preview',
                    'Library komponen yang lengkap'
                ]
            ],
            [
                'title' => 'E-commerce Ready',
                'description' => 'Fitur toko online lengkap dengan payment gateway',
                'icon' => '🛒',
                'details' => [
                    'Manajemen produk dan inventory',
                    'Multiple payment methods',
                    'Integrasi kurir pengiriman'
                ]
            ],
            [
                'title' => 'Hosting & Domain',
                'description' => 'Hosting cepat dan domain gratis dengan setiap paket',
                'icon' => '🌐',
                'details' => [
                    'SSL certificate gratis',
                    'CDN global',
                    'Backup otomatis harian'
                ]
            ],
            [
                'title' => 'Analytics & SEO',
                'description' => 'Tools analitik dan optimasi SEO terintegrasi',
                'icon' => '📊',
                'details' => [
                    'Google Analytics integration',
                    'SEO tools dan suggestions',
                    'Performance monitoring'
                ]
            ],
            [
                'title' => 'Support 24/7',
                'description' => 'Tim support siap membantu kapan saja',
                'icon' => '💬',
                'details' => [
                    'Live chat support',
                    'Email support',
                    'Video tutorial lengkap'
                ]
            ]
        ];

        return view('features.index', [
            'title' => 'Fitur',
            'description' => 'Fitur-fitur lengkap Temploka untuk bisnis Anda',
            'features' => $features
        ]);
    }
}