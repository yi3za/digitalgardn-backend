<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceFichier>
 */
class ServiceFichierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chemin' => "",
            'type' => $this->faker->randomElement(['image']),
            'ordre' => $this->faker->numberBetween(0, 5),
            'est_principale' => false,
        ];
    }
}
