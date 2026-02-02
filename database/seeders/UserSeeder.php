<?php

namespace Database\Seeders;

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
        User::factory(20)->create();
    }
}
