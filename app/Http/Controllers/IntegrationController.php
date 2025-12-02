<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    /**
     * Menampilkan halaman integrasi
     */
    public function index()
    {
        $integrations = [
            [
                'category' => 'Payment Gateway',
                'items' => [
                    [
                        'name' => 'Midtrans',
                        'description' => 'Payment gateway terkemuka di Indonesia',
                        'logo' => '💳',
                        'status' => 'active'
                    ],
                    [
                        'name' => 'Xendit',
                        'description' => 'Solusi pembayaran digital lengkap',
                        'logo' => '💰',
                        'status' => 'active'
                    ],
                    [
                        'name' => 'Stripe',
                        'description' => 'Payment gateway internasional',
                        'logo' => '🌍',
                        'status' => 'coming_soon'
                    ]
                ]
            ],
            [
                'category' => 'Shipping & Logistics',
                'items' => [
                    [
                        'name' => 'JNE',
                        'description' => 'Layanan pengiriman terpercaya',
                        'logo' => '🚚',
                        'status' => 'active'
                    ],
                    [
                        'name' => 'GoSend',
                        'description' => 'Pengiriman same-day delivery',
                        'logo' => '🏍️',
                        'status' => 'active'
                    ],
                    [
                        'name' => 'GrabExpress',
                        'description' => 'Ekspedisi on-demand',
                        'logo' => '📦',
                        'status' => 'active'
                    ]
                ]
            ],
            [
                'category' => 'Marketing & Analytics',
                'items' => [
                    [
                        'name' => 'Google Analytics',
                        'description' => 'Analytics dan tracking pengunjung',
                        'logo' => '📈',
                        'status' => 'active'
                    ],
                    [
                        'name' => 'Google Search Console',
                        'description' => 'Optimasi SEO dan performa website',
                        'logo' => '🔍',
                        'status' => 'active'
                    ],
                    [
                        'name' => 'Mailchimp',
                        'description' => 'Email marketing automation',
                        'logo' => '✉️',
                        'status' => 'coming_soon'
                    ]
                ]
            ]
        ];

        return view('integrations.index', [
            'title' => 'Integrasi',
            'description' => 'Integrasi dengan platform lain untuk workflow yang lebih baik',
            'integrations' => $integrations
        ]);
    }
}