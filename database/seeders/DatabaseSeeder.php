<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(DemoDataSeeder::class);

        $admin = User::firstOrCreate(
            ['email' => 'admin@parfums-marketplace.test'],
            [
                'name' => 'Administrateur',
                'password' => bcrypt('Admin@1234'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');
    }
}