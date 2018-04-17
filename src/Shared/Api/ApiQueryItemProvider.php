<?php

namespace App\Shared\Api;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use function Clue\React\Block\await;
use Prooph\ServiceBus\Exception\MessageDispatchException;
use Prooph\ServiceBus\QueryBus;
use React\EventLoop\Factory;

class ApiQueryItemProvider implements ItemDataProviderInterface
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
     * Retrieves an item.
     *
     * @param string $resourceClass
     * @param int|string $id
     *
     * @param string|null $operationName
     * @param array $context
     * @return object|null
     * @throws \Exception
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        if (!in_array(ItemQueryAwareResource::class, class_implements($resourceClass))) {
            throw new \RuntimeException(
                sprintf(
                    'Resource "%s" must implement "%s" to execute item query on it',
                    $resourceClass,
                    ItemQueryAwareResource::class
                )
            );
        }

        /** @var ItemQueryAwareResource $resourceClass */
        $queryClass = $resourceClass::itemQueryClass();
        $query = new $queryClass($id);

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