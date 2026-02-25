<?php

namespace App\Repositories\Contracts;

interface CollectionRepositoryInterface
{
    public function featured(int $limit = 6);

    public function paginateActive(array $filters, int $perPage = 20);

    public function findBySlug(string $slug);
}
