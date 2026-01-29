<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceFichier;
use Illuminate\Database\Seeder;

class ServiceFichierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = Service::all();
        foreach ($services as $service) {
            $count = rand(1, 4);
            ServiceFichier::factory($count)->create(['service_id' => $service->id]);
        }
    }
}
