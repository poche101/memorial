<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            MemorialSeeder::class,
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@christembassylz5.org'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'),
            ]
        );
        $admin->assignRole('super_admin');
    }
}
