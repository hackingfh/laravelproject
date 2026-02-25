<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use App\Models\User;

interface OrderRepositoryInterface
{
    public function listForUser(User $user, array $filters, int $perPage = 10);

    public function findByIdForUser(User $user, int $id): ?Order;

    public function create(array $data): Order;
}
