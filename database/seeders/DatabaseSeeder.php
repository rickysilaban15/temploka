<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Admin and User Seeder
            AdminUserSeeder::class,
            
            // Category Seeder (sudah ada)
            CategorySeeder::class,
            
            // Template Seeder (sudah ada)
            TemplateSeeder::class,
            
            // Pricing Plan Seeder (sudah ada)
            PricingPlanSeeder::class,
            
            // Seeder baru untuk view-view
            DashboardDataSeeder::class,
            IntegrationSeeder::class,
            ModuleStatsSeeder::class,
            WebinarSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ All seeders completed successfully!');
        $this->command->newLine();
        $this->command->info('📊 Summary:');
        $this->command->info('   - Admin & Users created');
        $this->command->info('   - Categories created');
        $this->command->info('   - Templates created');
        $this->command->info('   - Pricing Plans created');
        $this->command->info('   - Dashboard Modules created');
        $this->command->info('   - Integrations created (12 platforms)');
        $this->command->info('   - Business Module Stats created');
        $this->command->info('   - Webinars created (6 webinars)');
        $this->command->newLine();
        $this->command->info('🚀 Your application is ready to use!');
    }
}