<?php

namespace App\Book\Query\BookItem;

use App\Book\Application\Resource\BookResource;
use Ramsey\Uuid\Uuid;
use React\Promise\Deferred;

class BookItemQueryHandler
{
    public function __invoke(BookItemQuery $query, Deferred $deferred)
    {
        // Dummy data
        $x = new BookResource();
        $x->setId(Uuid::uuid4());
        $x->setName('test');
        $x->setAuthor('aaa');

        $deferred->resolve($x);
    }
}