<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data untuk Quick Access Modules di Dashboard
        $quickAccessModules = [
            [
                'title' => 'Invoice & Keuangan',
                'description' => 'Kelola invoice pelanggan',
                'icon' => 'fas fa-file-invoice',
                'icon_color' => 'text-green-600',
                'bg_color' => 'bg-green-50',
                'route' => 'dashboard.modules.invoice',
                'is_active' => true,
                'order' => 1
            ],
            [
                'title' => 'Katalog Produk',
                'description' => 'Manajemen produk',
                'icon' => 'fas fa-box-open',
                'icon_color' => 'text-blue-600',
                'bg_color' => 'bg-blue-50',
                'route' => 'dashboard.modules.products',
                'is_active' => true,
                'order' => 2
            ],
            [
                'title' => 'CRM',
                'description' => 'Kelola pelanggan',
                'icon' => 'fas fa-users',
                'icon_color' => 'text-purple-600',
                'bg_color' => 'bg-purple-50',
                'route' => 'dashboard.modules.crm',
                'is_active' => true,
                'order' => 3
            ],
            [
                'title' => 'E-Commerce',
                'description' => 'Kelola pesanan',
                'icon' => 'fas fa-shopping-cart',
                'icon_color' => 'text-orange-600',
                'bg_color' => 'bg-orange-50',
                'route' => 'dashboard.modules.ecommerce',
                'is_active' => true,
                'order' => 4
            ]
        ];

        // Buat tabel quick_access_modules jika belum ada
        if (!Schema::hasTable('quick_access_modules')) {
            Schema::create('quick_access_modules', function ($table) {
                $table->id();
                $table->string('title');
                $table->string('description');
                $table->string('icon');
                $table->string('icon_color');
                $table->string('bg_color');
                $table->string('route');
                $table->boolean('is_active')->default(true);
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        }

        // Insert data modules
        foreach ($quickAccessModules as $module) {
            DB::table('quick_access_modules')->insert(array_merge($module, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        $this->command->info('Quick Access Modules seeded successfully!');
    }
}