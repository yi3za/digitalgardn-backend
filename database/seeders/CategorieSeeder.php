<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parents = Categorie::factory(5)->create();
        for ($i = 0; $i < 10; $i++) {
            $parent = $parents->random();
            Categorie::factory()->create(['parent_id' => $parent->id]);
        }
    }
}
