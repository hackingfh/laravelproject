<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    public function creating(Product $product): void
    {
        if (empty($product->slug)) {
            $base = Str::slug($product->name ?? Str::random(8));
            $slug = $base;
            $i = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $base.'-'.$i++;
            }
            $product->slug = $slug;
        }

        if (empty($product->sku)) {
            $base = 'SKU-'.Str::upper(Str::random(8));
            $sku = $base;
            $i = 1;
            while (Product::where('sku', $sku)->exists()) {
                $sku = $base.'-'.$i++;
            }
            $product->sku = $sku;
        }

        if (empty($product->reference)) {
            $ref = Str::upper(Str::random(7));
            while (Product::where('reference', $ref)->exists()) {
                $ref = Str::upper(Str::random(7));
            }
            $product->reference = $ref;
        }
    }
}
