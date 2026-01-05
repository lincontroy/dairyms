<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@dairyfarm.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Farm Manager',
            'email' => 'manager@dairyfarm.com',
            'password' => Hash::make('manager123'),
            'role' => 'manager',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Veterinarian',
            'email' => 'vet@dairyfarm.com',
            'password' => Hash::make('vet123'),
            'role' => 'vet',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Farm Staff',
            'email' => 'staff@dairyfarm.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
            'email_verified_at' => now(),
        ]);
    }
}