<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'is_admin' => true,
        ]);

        $collections = Collection::factory()->count(5)->create();
        $options = Option::factory()->count(3)->create();

        $collections->each(function ($collection) use ($options) {
            $products = Product::factory()->count(8)->create([
                'collection_id' => $collection->id,
            ]);

            foreach ($products as $product) {
                $product->options()->attach($options->random(2)->pluck('id')->all());
            }
        });

        $user = User::first();
        $orders = Order::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        foreach ($orders as $order) {
            $products = Product::inRandomOrder()->limit(2)->get();
            foreach ($products as $p) {
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'price_at_purchase' => $p->price,
                    'product_snapshot' => [
                        'name' => $p->name,
                        'reference' => $p->reference,
                        'sku' => $p->sku,
                        'price' => $p->price,
                    ],
                ]);
            }
        }
    }
}
