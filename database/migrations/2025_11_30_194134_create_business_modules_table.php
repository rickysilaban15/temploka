<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Skip jika tabel sudah ada
        if (!Schema::hasTable('business_modules')) {
            Schema::create('business_modules', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique(); // ✅ TAMBAHKAN INI
                $table->text('description')->nullable();
                $table->string('icon');
                $table->string('bg_color');
                $table->string('icon_color');
                $table->boolean('is_active')->default(true);
                $table->integer('order')->default(0);
                $table->timestamps();
            });

            // Insert default data
            DB::table('business_modules')->insert([
                [
                    'name' => 'Manajemen Produk',
                    'slug' => 'products', // ✅ TAMBAHKAN INI
                    'description' => 'Kelola produk & inventory',
                    'icon' => 'fas fa-box',
                    'bg_color' => 'bg-primary-50',
                    'icon_color' => 'text-primary',
                    'is_active' => true,
                    'order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Laporan Keuangan',
                    'slug' => 'finance', // ✅ TAMBAHKAN INI
                    'description' => 'Analisis pendapatan & profit',
                    'icon' => 'fas fa-chart-pie',
                    'bg_color' => 'bg-green-50',
                    'icon_color' => 'text-green-600',
                    'is_active' => true,
                    'order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Manajemen Pelanggan',
                    'slug' => 'customers', // ✅ TAMBAHKAN INI
                    'description' => 'Data & riwayat pelanggan',
                    'icon' => 'fas fa-users',
                    'bg_color' => 'bg-blue-50',
                    'icon_color' => 'text-blue-600',
                    'is_active' => true,
                    'order' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Pesanan & Pengiriman',
                    'slug' => 'orders', // ✅ TAMBAHKAN INI
                    'description' => 'Kelola pesanan & pengiriman',
                    'icon' => 'fas fa-shopping-cart',
                    'bg_color' => 'bg-purple-50',
                    'icon_color' => 'text-purple-600',
                    'is_active' => true,
                    'order' => 4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('business_modules');
    }
};