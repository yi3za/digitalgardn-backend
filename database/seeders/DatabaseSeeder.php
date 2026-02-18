<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProfilSeeder::class,
            CategorieSeeder::class,
            CompetenceSeeder::class,
            ServiceSeeder::class,
            ServiceFichierSeeder::class,
        ]);
    }
}
