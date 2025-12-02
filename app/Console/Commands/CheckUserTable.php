<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckUserTable extends Command
{
    protected $signature = 'check:user-table';
    protected $description = 'Check user table structure';

    public function handle()
    {
        $columns = Schema::getColumnListing('users');
        
        $this->info('Columns in users table:');
        foreach ($columns as $column) {
            $this->line("- $column");
        }

        // Cek specific columns
        $specificColumns = ['onboarding_completed', 'business_type', 'business_goals'];
        foreach ($specificColumns as $column) {
            if (in_array($column, $columns)) {
                $this->info("✓ $column exists");
            } else {
                $this->error("✗ $column does not exist");
            }
        }
    }
}