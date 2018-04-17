<?php

namespace App\Book\Query\BookCollection;

class BookCollectionQuery
{
    /**
     * @var array
     */
    private $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function filters()
    {
        return $this->filters;
    }
}