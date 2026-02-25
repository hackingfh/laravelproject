<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OptionFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->randomElement(['Couleur', 'Matière du bracelet', 'Boucle']);
        $values = [
            ['label' => 'Noir', 'value' => 'noir'],
            ['label' => 'Marron', 'value' => 'marron'],
            ['label' => 'Acier', 'value' => 'acier'],
        ];

        return [
            'name' => $name,
            'values' => $values,
            'type' => $this->faker->randomElement(['select', 'radio', 'checkbox']),
            'required' => $this->faker->boolean(30),
        ];
    }
}
