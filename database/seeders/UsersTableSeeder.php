<?php
// database/seeders/UsersTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@farm.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true
        ]);

        User::create([
            'name' => 'Staff User',
            'email' => 'staff@farm.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_active' => true
        ]);
    }
}