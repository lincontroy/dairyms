<?php
// database/seeders/AnimalsTableSeeder.php
namespace Database\Seeders;

use App\Models\Animal;
use Illuminate\Database\Seeder;

class AnimalsTableSeeder extends Seeder
{
    public function run()
    {
        Animal::create([
            'animal_id' => 'COW-001',
            'name' => 'Daisy',
            'ear_tag' => 'ET-001',
            'breed' => 'Holstein Friesian',
            'date_of_birth' => '2020-05-15',
            'sex' => 'Female',
            'source' => 'born',
            'status' => 'lactating',
            'date_added' => '2020-05-15',
            'is_active' => true
        ]);

        Animal::create([
            'animal_id' => 'COW-002',
            'name' => 'Bella',
            'ear_tag' => 'ET-002',
            'breed' => 'Jersey',
            'date_of_birth' => '2021-03-20',
            'sex' => 'Female',
            'source' => 'born',
            'status' => 'lactating',
            'date_added' => '2021-03-20',
            'is_active' => true
        ]);
    }
}