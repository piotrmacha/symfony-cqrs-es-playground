<?php

namespace App\Shared\Collection;

class PaginatedCollection implements \Countable
{
    /**
     * @var PaginatedCollectionAdapter
     */
    private $adapter;
    /**
     * @var int
     */
    private $itemsPerPage;

    public function __construct(PaginatedCollectionAdapter $adapter, int $itemsPerPage)
    {
        $this->adapter = $adapter;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function count(): int
    {
        return $this->adapter->count();
    }

    public function pages(): int
    {
        return ceil($this->count() / $this->itemsPerPage);
    }

    public function itemsPerPage()
    {
        return $this->itemsPerPage;
    }

    public function fetch(int $page)
    {
        return $this->adapter->fetch($this->itemsPerPage, ($page - 1) * $this->itemsPerPage);
    }
}