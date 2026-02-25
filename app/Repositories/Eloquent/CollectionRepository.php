<?php

namespace App\Repositories\Eloquent;

use App\Models\Collection;
use App\Repositories\Contracts\CollectionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CollectionRepository implements CollectionRepositoryInterface
{
    public function featured(int $limit = 6)
    {
        if (DB::connection()->getDriverName() === 'sqlite' && ! extension_loaded('pdo_sqlite')) {
            return collect();
        }

        return Collection::query()
            ->where('is_active', true)
            ->orderBy('period_start', 'desc')
            ->limit($limit)
            ->get();
    }

    public function paginateActive(array $filters, int $perPage = 20)
    {
        $q = Collection::query()->where('is_active', true);

        if (! empty($filters['category'])) {
            $q->where('name', 'like', '%'.$filters['category'].'%');
        }
        if (! empty($filters['created_from'])) {
            $q->whereDate('created_at', '>=', $filters['created_from']);
        }
        if (! empty($filters['created_to'])) {
            $q->whereDate('created_at', '<=', $filters['created_to']);
        }
        if (! empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'name':
                    $q->orderBy('name');
                    break;
                case 'date':
                    $q->orderBy('created_at', 'desc');
                    break;
                case 'popularity':
                    $q->withCount('products')->orderBy('products_count', 'desc');
                    break;
                default:
                    $q->orderBy('name');
                    break;
            }
        } else {
            $q->orderBy('name');
        }

        return $q->paginate($perPage);
    }

    public function findBySlug(string $slug)
    {
        return Collection::query()->where('slug', $slug)->first();
    }
}
