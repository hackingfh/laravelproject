<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $number = 'CMD-'.$this->faker->date('Ym').'-'.Str::upper(Str::random(6));

        return [
            'user_id' => null,
            'order_number' => $number,
            'total' => $this->faker->randomFloat(2, 500, 15000),
            'status' => $this->faker->randomElement(['pending', 'paid', 'shipped', 'delivered']),
            'shipping_address' => [
                'name' => $this->faker->name(),
                'street' => $this->faker->streetAddress(),
                'city' => $this->faker->city(),
                'zip' => $this->faker->postcode(),
                'country' => $this->faker->country(),
            ],
            'payment_method' => $this->faker->randomElement(['card', 'paypal', 'transfer']),
            'payment_status' => $this->faker->randomElement(['pending', 'authorized', 'captured', 'refunded']),
            'tracking_number' => $this->faker->optional()->uuid(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
