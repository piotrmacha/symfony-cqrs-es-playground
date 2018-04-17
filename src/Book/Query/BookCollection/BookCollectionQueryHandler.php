<?php

namespace App\Book\Query\BookCollection;

use App\Book\Application\Resource\BookResource;
use Ramsey\Uuid\Uuid;
use React\Promise\Deferred;

class BookCollectionQueryHandler
{
    public function __invoke(BookCollectionQuery $query, Deferred $deferred)
    {
        // Dummy data
        $x = new BookResource();
        $x->setId(Uuid::uuid4());
        $x->setName('test');
        $x->setAuthor('aaa');

        $deferred->resolve([$x, $x]);
    }
}