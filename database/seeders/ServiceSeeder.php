<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $freelances = User::where('role', 'freelance')->get();
        foreach ($freelances as $freelance) {
            $count = rand(1, 20);
            $services = Service::factory($count)->create(['user_id' => $freelance->id]);
            foreach ($services as $service) {
                $categoriesIds = Categorie::whereNotNull('parent_id')->inRandomOrder()->take(rand(1, 10))->pluck('id')->toArray();
                $service->categories()->attach($categoriesIds);
            }
        }
    }
}
