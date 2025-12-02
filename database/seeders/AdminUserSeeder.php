<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'temploka384@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Sandi154.'),
                'email_verified_at' => now(),
            ]
        );
    }
}
