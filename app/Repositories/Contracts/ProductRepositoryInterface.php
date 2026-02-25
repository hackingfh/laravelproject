<?php

namespace App\Repositories\Contracts;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function search(array $filters, int $perPage = 30);

    public function fullText(string $q, int $perPage = 30);

    public function findBySlug(string $slug): ?Product;

    public function recommended(Product $product, int $limit = 6);
}
