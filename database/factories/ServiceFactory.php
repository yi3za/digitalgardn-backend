<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titre = $this->faker->unique()->sentence(3); // عنوان الخدمة
        return [
            'user_id' => User::factory(),
            'titre' => $titre,
            'slug' => Str::slug($titre),
            'description' => $this->faker->paragraphs(2, true),
            'prix_base' => $this->faker->randomFloat(2, 10, 500),
            'delai_livraison' => $this->faker->numberBetween(1, 30),
            'revisions' => $this->faker->numberBetween(0, 5),
            'statut' => $this->faker->randomElement(['brouillon', 'publie', 'en_pause', 'en_attente_approbation', 'rejete']),
            'ventes' => $this->faker->numberBetween(0, 1000),
            'note_moyenne' => $this->faker->randomFloat(2, 0, 5),
            'vues' => $this->faker->numberBetween(0, 10000),
        ];
    }
}
