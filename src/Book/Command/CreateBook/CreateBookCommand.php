<?php

namespace App\Book\Command\CreateBook;

use App\Book\Application\Dto\Book;

class CreateBookCommand
{
    /**
     * @var Book
     */
    private $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function book()
    {
        return $this->book;
    }
}