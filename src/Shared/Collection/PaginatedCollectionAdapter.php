<?php

namespace App\Shared\Collection;

interface PaginatedCollectionAdapter
{
    public function count(): int;

    public function fetch(int $limit, int $offset = 0): array;
}