<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = ucfirst($this->faker->unique()->words(3, true));

        return [
            'collection_id' => null,
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(4),
            'reference' => 'MT-' . Str::upper(Str::random(6)),
            'price' => $this->faker->randomFloat(2, 500, 25000),
            'material' => $this->faker->randomElement(['acier', 'or', 'titane']),
            'movement' => $this->faker->randomElement(['automatique', 'manuel', 'quartz']),
            'complications' => $this->faker->randomElements(['chronographe', 'date', 'GMT', 'tourbillon'], 2),
            'images' => [$this->faker->imageUrl(), $this->faker->imageUrl()],
            'case_diameter' => $this->faker->numberBetween(36, 45) . 'mm',
            'case_thickness' => $this->faker->numberBetween(8, 15) . 'mm',
            'stock' => $this->faker->numberBetween(0, 50),
            'description' => $this->faker->paragraph(),
            'sku' => 'SKU-' . Str::upper(Str::random(8)),
            'is_visible' => true,
            'is_swiss_made' => true,
            'warranty_years' => 2,
        ];
    }
}
