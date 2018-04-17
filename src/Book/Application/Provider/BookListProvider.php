<?php

namespace App\Book\Application\Provider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Book\Application\Dto\Book;
use Ramsey\Uuid\Uuid;

class BookListProvider implements RestrictedDataProviderInterface, ContextAwareCollectionDataProviderInterface
{
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Book::class;
    }

    /**
     * Retrieves a collection.
     *
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     * @return array|\Traversable
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $x = new Book();
        $x->setId(Uuid::uuid4());
        $x->setName('test');
        $x->setAuthor('aaa');
        return [$x, $x];
    }
}