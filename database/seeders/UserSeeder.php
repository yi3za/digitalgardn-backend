<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'yaaza',
            'username' => 'yaaza',
            'email' => 'yaaza@gmail.com',
            'role' => 'freelance',
            'password' => 'yaazayaaza',
        ]);
        $users = User::factory(20)->create();
        foreach ($users as $user) {
            $competencesIds = Competence::whereNotNull('parent_id')->inRandomOrder()->take(rand(1, 10))->pluck('id')->toArray();
            $user->competences()->attach($competencesIds);
        }
    }
}
