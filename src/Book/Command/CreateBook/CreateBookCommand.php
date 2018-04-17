<?php

namespace App\Book\Command\CreateBook;

use App\Book\Application\Resource\BookResource;

class CreateBookCommand
{
    /**
     * @var BookResource
     */
    private $book;

    public function __construct(BookResource $book)
    {
        $this->book = $book;
    }

    public function book()
    {
        return $this->book;
    }
}