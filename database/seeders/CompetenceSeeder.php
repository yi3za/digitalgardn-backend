<?php

namespace Database\Seeders;

use App\Models\Competence;
use Illuminate\Database\Seeder;

class CompetenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parents = Competence::factory(10)->create();
        for ($i = 0; $i < 30; $i++) {
            $parent = $parents->random();
            Competence::factory()->create(['parent_id' => $parent->id]);
        }
    }
}
