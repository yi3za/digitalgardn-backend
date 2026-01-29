<?php

namespace Database\Seeders;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $freelances = User::where('role', 'freelance')->get();
        foreach ($freelances as $freelance) {
            Profil::factory()->create(['user_id' => $freelance->id]);
        }
    }
}
