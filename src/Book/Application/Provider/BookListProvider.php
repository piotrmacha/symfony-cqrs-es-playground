<?php

namespace App\Book\Application\Provider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Book\Application\Dto\Book;
use Ramsey\Uuid\Uuid;

class BookListProvider implements CollectionDataProviderInterface
{
    public function supports(string $resourceClass, string $operationName = null): bool
    {
        return $resourceClass === Book::class;
    }

    /**
     * Retrieves a collection.
     *
     * @param string $resourceClass
     * @param string|null $operationName
     * @return array|\Traversable
     */
    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $x = new Book();
        $x->setId(Uuid::uuid4());
        $x->setName('test');
        $x->setAuthor('aaa');
        return [$x, $x];
    }
}