<?php

namespace App\Shared\Api;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use function Clue\React\Block\await;
use Prooph\ServiceBus\Exception\MessageDispatchException;
use Prooph\ServiceBus\QueryBus;
use React\EventLoop\Factory;

class ApiQueryCollectionProvider implements ContextAwareCollectionDataProviderInterface
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * Retrieves a collection.
     *
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     * @return array|\Traversable
     * @throws \Exception
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        if (!in_array(CollectionQueryAwareResource::class, class_implements($resourceClass))) {
            throw new \RuntimeException(
                sprintf(
                    'Resource "%s" must implement "%s" to execute collection query on it',
                    $resourceClass,
                    CollectionQueryAwareResource::class
                )
            );
        }

        $filters = [];
        if (array_key_exists('filters', $context)) {
            $filters = $context['filters'];
        }

        /** @var CollectionQueryAwareResource $resourceClass */
        $queryClass = $resourceClass::collectionQueryClass();
        $query = new $queryClass($filters);

        try {
            // Async code is fine until you need to integrate it with synchronous libraries
            return await($this->queryBus->dispatch($query), Factory::create());
        } catch (MessageDispatchException $exception) {
            // API Platform shows only the newest exception message, so we need to rethrow the second in order
            $previous = $exception->getPrevious();
            if ($previous instanceof \Exception) {
                throw $previous;
            }
            throw $exception;
        }
    }
}