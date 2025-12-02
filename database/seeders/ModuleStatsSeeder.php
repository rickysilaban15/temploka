<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModuleStatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Business Modules
        $modules = [
            [
                'name' => 'Invoice & Keuangan',
                'slug' => 'invoice',
                'description' => 'Kelola invoice dan pembayaran pelanggan',
                'icon' => 'fas fa-file-invoice',
                'icon_color' => 'text-green-600',
                'bg_color' => 'bg-green-50',
                'is_active' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Katalog Produk',
                'slug' => 'products',
                'description' => 'Kelola produk dan stok inventori',
                'icon' => 'fas fa-box-open',
                'icon_color' => 'text-blue-600',
                'bg_color' => 'bg-blue-50',
                'is_active' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Customer Relationship Management',
                'slug' => 'crm',
                'description' => 'Kelola hubungan dengan pelanggan',
                'icon' => 'fas fa-users',
                'icon_color' => 'text-purple-600',
                'bg_color' => 'bg-purple-50',
                'is_active' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'E-commerce & Orders',
                'slug' => 'ecommerce',
                'description' => 'Kelola pesanan dan pengiriman',
                'icon' => 'fas fa-shopping-cart',
                'icon_color' => 'text-orange-600',
                'bg_color' => 'bg-orange-50',
                'is_active' => true,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert business modules menggunakan DB transaction untuk safety
        DB::transaction(function () use ($modules) {
            foreach ($modules as $module) {
                DB::table('business_modules')->insertOrIgnore($module);
            }
        });

        // Insert sample data untuk invoices
        $this->seedInvoices();

        // Insert sample data untuk products
        $this->seedProducts();

        // Insert sample data untuk customers
        $this->seedCustomers();

        $this->command->info('Business Modules and sample data seeded successfully!');
    }

    /**
     * Seed sample invoices
     */
    private function seedInvoices(): void
    {
        if (!Schema::hasTable('invoices')) {
            $this->command->warn('Invoices table does not exist, skipping invoice seeding');
            return;
        }

        $invoices = [
            [
                'invoice_number' => 'INV-001',
                'user_id' => 1,
                'amount' => 110000.00,
                'status' => 'pending',
                'due_date' => now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'invoice_number' => 'INV-002',
                'user_id' => 1,
                'amount' => 250000.00,
                'status' => 'paid',
                'due_date' => now()->addDays(14),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(3)
            ],
            [
                'invoice_number' => 'INV-003',
                'user_id' => 1,
                'amount' => 175000.00,
                'status' => 'overdue',
                'due_date' => now()->subDays(3),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10)
            ],
        ];

        DB::transaction(function () use ($invoices) {
            foreach ($invoices as $invoice) {
                DB::table('invoices')->insertOrIgnore($invoice);
            }
        });
    }

    /**
     * Seed sample products
     */
    private function seedProducts(): void
    {
        if (!Schema::hasTable('products')) {
            $this->command->warn('Products table does not exist, skipping product seeding');
            return;
        }

        $products = [
            [
                'sku' => 'PRD-001',
                'user_id' => 1,
                'category_id' => 1,
                'name' => 'Produk Premium A',
                'slug' => 'produk-premium-a',
                'description' => 'Produk unggulan dengan kualitas terbaik',
                'price' => 250000.00,
                'cost' => 150000.00,
                'stock' => 50,
                'min_stock' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'sku' => 'PRD-002',
                'user_id' => 1,
                'category_id' => 1,
                'name' => 'Produk Reguler B',
                'slug' => 'produk-reguler-b',
                'description' => 'Produk dengan harga terjangkau',
                'price' => 125000.00,
                'cost' => 75000.00,
                'stock' => 100,
                'min_stock' => 20,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'sku' => 'PRD-003',
                'user_id' => 1,
                'category_id' => 1,
                'name' => 'Produk Spesial C',
                'slug' => 'produk-spesial-c',
                'description' => 'Edisi terbatas dengan fitur eksklusif',
                'price' => 500000.00,
                'cost' => 300000.00,
                'stock' => 15,
                'min_stock' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::transaction(function () use ($products) {
            foreach ($products as $product) {
                DB::table('products')->insertOrIgnore($product);
            }
        });
    }

    /**
     * Seed sample customers
     */
    private function seedCustomers(): void
    {
        if (!Schema::hasTable('customers')) {
            $this->command->warn('Customers table does not exist, skipping customer seeding');
            return;
        }

        $customers = [
            [
                'email' => 'pelanggan1@example.com',
                'user_id' => 1,
                'name' => 'Budi Santoso',
                'phone' => '08123456789',
                'address' => 'Jl. Sudirman No. 123',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '12190',
                'type' => 'individual', // Ubah dari 'vip' ke 'individual' atau 'business'
                'total_spent' => 2500000.00,
                'total_orders' => 15,
                'last_order_date' => now()->subDays(5),
                'notes' => 'Pelanggan VIP, prioritas tinggi',
                'created_at' => now()->subMonths(6),
                'updated_at' => now()
            ],
            [
                'email' => 'pelanggan2@example.com',
                'user_id' => 1,
                'name' => 'Siti Nurhaliza',
                'phone' => '08234567890',
                'address' => 'Jl. Gatot Subroto No. 45',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40262',
                'type' => 'individual', // Ubah dari 'regular' ke 'individual'
                'total_spent' => 750000.00,
                'total_orders' => 8,
                'last_order_date' => now()->subDays(15),
                'notes' => 'Pelanggan reguler',
                'created_at' => now()->subMonths(3),
                'updated_at' => now()
            ],
            [
                'email' => 'pelanggan3@example.com',
                'user_id' => 1,
                'name' => 'Ahmad Hidayat',
                'phone' => '08345678901',
                'address' => 'Jl. Malioboro No. 78',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'postal_code' => '55271',
                'type' => 'business', // Ubah dari 'new' ke 'business'
                'total_spent' => 150000.00,
                'total_orders' => 2,
                'last_order_date' => now()->subDays(2),
                'notes' => 'Pelanggan baru',
                'created_at' => now()->subDays(30),
                'updated_at' => now()
            ],
        ];

        DB::transaction(function () use ($customers) {
            foreach ($customers as $customer) {
                DB::table('customers')->insertOrIgnore($customer);
            }
        });
    }
}