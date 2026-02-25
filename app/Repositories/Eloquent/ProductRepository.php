<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function search(array $filters, int $perPage = 30)
    {
        if (DB::connection()->getDriverName() === 'sqlite' && !extension_loaded('pdo_sqlite')) {
            return new LengthAwarePaginator([], 0, $perPage, 1);
        }
        $q = Product::query()->with(['collection', 'media'])->where('is_visible', true);

        if (!empty($filters['q'])) {
            $term = $filters['q'];
            $q->where(function ($w) use ($term) {
                $w->where('name', 'like', '%' . $term . '%')
                    ->orWhere('reference', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%');
            });
        }
        if (!empty($filters['price_min'])) {
            $q->where('price', '>=', (float) $filters['price_min']);
        }
        if (!empty($filters['price_max'])) {
            $q->where('price', '<=', (float) $filters['price_max']);
        }
        if (!empty($filters['brand'])) {
            $q->whereHas('collection', fn($c) => $c->where('name', 'like', '%' . $filters['brand'] . '%'));
        }
        if (!empty($filters['category'])) {
            $q->whereHas('collection', fn($c) => $c->where('slug', $filters['category']));
        }
        if (!empty($filters['collection'])) {
            $q->whereHas('collection', fn($c) => $c->where('slug', $filters['collection']));
        }
        if (!empty($filters['availability'])) {
            if ($filters['availability'] === 'in_stock') {
                $q->where('stock', '>', 0);
            } elseif ($filters['availability'] === 'out_of_stock') {
                $q->where('stock', '=', 0);
            }
        }
        if (!empty($filters['sort'])) {
            foreach (explode(',', $filters['sort']) as $criterion) {
                [$field, $dir] = array_pad(explode(':', $criterion), 2, 'asc');
                switch ($field) {
                    case 'price':
                        $q->orderBy('price', $dir);
                        break;
                    case 'name':
                        $q->orderBy('name', $dir);
                        break;
                    case 'date':
                        $q->orderBy('created_at', $dir);
                        break;
                }
            }
        } else {
            $q->orderBy('created_at', 'desc');
        }

        return $q->paginate($perPage);
    }

    public function fullText(string $q, int $perPage = 30)
    {
        return $this->search(['q' => $q], $perPage);
    }

    public function findBySlug(string $slug): ?Product
    {
        return Product::query()->with(['collection', 'media', 'options'])->where('slug', $slug)->first();
    }

    public function recommended(Product $product, int $limit = 6)
    {
        return Product::query()
            ->where('collection_id', $product->collection_id)
            ->where('id', '<>', $product->id)
            ->where('is_visible', true)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
