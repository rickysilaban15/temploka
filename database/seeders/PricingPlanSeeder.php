<?php

namespace Database\Seeders;

use App\Models\PricingPlan;
use Illuminate\Database\Seeder;

class PricingPlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Sempurna untuk memulai dan mencoba platform',
                'price' => 0,
                'billing_period' => 'monthly',
                'discount_percentage' => 0,
                'features' => json_encode([
                    '5 Template Gratis',
                    '1 GB Storage',
                    'Basic Support',
                    'Email Support',
                    'Watermark Temploka'
                ]),
                'template_limit' => 5,
                'storage_gb' => 1,
                'priority_support' => false,
                'custom_domain' => false,
                'white_label' => false,
                'api_access' => false,
                'is_featured' => false,
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Untuk bisnis kecil yang sedang berkembang',
                'price' => 99000,
                'billing_period' => 'monthly',
                'discount_percentage' => 0,
                'features' => json_encode([
                    '20 Template Premium',
                    '5 GB Storage',
                    'Priority Email Support',
                    'Custom Domain',
                    'Tanpa Watermark',
                    'Analytics Basic'
                ]),
                'template_limit' => 20,
                'storage_gb' => 5,
                'priority_support' => false,
                'custom_domain' => true,
                'white_label' => false,
                'api_access' => false,
                'is_featured' => false,
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'Paling populer untuk bisnis yang berkembang pesat',
                'price' => 199000,
                'billing_period' => 'monthly',
                'discount_percentage' => 0,
                'features' => json_encode([
                    'Unlimited Template',
                    '20 GB Storage',
                    'Priority Support 24/7',
                    'Custom Domain',
                    'White Label',
                    'Advanced Analytics',
                    'SEO Tools',
                    'Team Collaboration (5 users)'
                ]),
                'template_limit' => null,
                'storage_gb' => 20,
                'priority_support' => true,
                'custom_domain' => true,
                'white_label' => true,
                'api_access' => false,
                'is_featured' => true,
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Solusi lengkap untuk perusahaan besar',
                'price' => 499000,
                'billing_period' => 'monthly',
                'discount_percentage' => 0,
                'features' => json_encode([
                    'Unlimited Template',
                    'Unlimited Storage',
                    'Dedicated Support',
                    'Custom Domain',
                    'White Label',
                    'Advanced Analytics',
                    'API Access',
                    'Team Collaboration (Unlimited)',
                    'Custom Development',
                    'SLA 99.9%'
                ]),
                'template_limit' => null,
                'storage_gb' => 999,
                'priority_support' => true,
                'custom_domain' => true,
                'white_label' => true,
                'api_access' => true,
                'is_featured' => false,
                'is_active' => true,
                'order' => 4,
            ]
        ];

        foreach ($plans as $plan) {
            PricingPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }

        $this->command->info('4 Pricing plans seeded successfully!');
    }
}