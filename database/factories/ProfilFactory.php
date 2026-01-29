<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profil>
 */
class ProfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => $this->faker->jobTitle(),
            'biographie' => $this->faker->paragraphs(2, true),
            'image_couverture' => $this->faker->imageUrl(600, 200),
            'site_web' => $this->faker->url(),
            'liens_sociaux' => json_encode([
                'facebook' => $this->faker->url(),
                'twitter' => $this->faker->url(),
                'linkedin' => $this->faker->url(),
            ]),
        ];
    }
}
