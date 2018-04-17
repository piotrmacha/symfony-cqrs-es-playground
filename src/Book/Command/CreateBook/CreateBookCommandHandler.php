<?php

namespace App\Book\Command\CreateBook;

use App\Book\Domain\Book;
use Ramsey\Uuid\Uuid;

class CreateBookCommandHandler
{
    public function __invoke(CreateBookCommand $command)
    {
        $dto = $command->book();

        $id = Uuid::uuid4();
        $dto->setId($id);

        $book = Book::create($id->toString(), $dto->getName(), $dto->getAuthor());
    }
}