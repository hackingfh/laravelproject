<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function listForUser(User $user, array $filters, int $perPage = 10)
    {
        $q = Order::query()->where('user_id', $user->id)->with('items.product');
        if (! empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }
        if (! empty($filters['date_from'])) {
            $q->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $q->whereDate('created_at', '<=', $filters['date_to']);
        }
        if (! empty($filters['amount_min'])) {
            $q->where('total', '>=', (float) $filters['amount_min']);
        }
        if (! empty($filters['amount_max'])) {
            $q->where('total', '<=', (float) $filters['amount_max']);
        }

        return $q->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findByIdForUser(User $user, int $id): ?Order
    {
        return Order::query()->where('user_id', $user->id)->with('items.product')->find($id);
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }
}
