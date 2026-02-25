<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CollectionFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(4),
            'description' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(),
            'period_start' => $this->faker->numberBetween(1950, 2020),
            'period_end' => $this->faker->numberBetween(1950, 2025),
            'is_active' => true,
        ];
    }
}
