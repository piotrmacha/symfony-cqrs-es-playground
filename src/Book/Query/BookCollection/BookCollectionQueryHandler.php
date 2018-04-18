<?php

namespace App\Book\Query\BookCollection;

use App\Book\Application\Resource\BookResource;
use App\Shared\Collection\PaginatedCollection;
use App\Shared\Collection\PaginatedCollectionAdapter;
use Ramsey\Uuid\Uuid;
use React\Promise\Deferred;

class BookCollectionQueryHandler
{
    public function __invoke(BookCollectionQuery $query, Deferred $deferred)
    {
        try {
            $collection = new PaginatedCollection(
                new class implements PaginatedCollectionAdapter
                {
                    public function count(): int
                    {
                        return 10;
                    }

                    public function fetch(int $limit, int $offset = 0): array
                    {
                        $list = [];
                        for ($i = 0; $i < 10; ++$i) {
                            $x = new BookResource();
                            $x->setId(Uuid::uuid4());
                            $x->setName('test = ' . $i);
                            $x->setAuthor('aaa');
                            $list[] = $x;
                        }

                        return array_slice($list, $offset, $limit);
                    }
                },
                2
            );
            $deferred->resolve($collection);
        } catch (\Throwable $e) {
            $deferred->reject($e->getMessage());
        }
    }
}