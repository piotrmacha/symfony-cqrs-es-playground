<?php

namespace App\Shared\CommandBus;

use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Plugin;
use Prooph\ServiceBus\Plugin\Router\MessageBusRouterPlugin;
use Prooph\ServiceBus\QueryBus;

class CommandQueryBusFactory
{
    public static function createCommandBus(MessageBusRouterPlugin $router): CommandBus
    {
        $commandBus = new CommandBus();
        /** @var Plugin $router */
        $router->attachToMessageBus($commandBus);
        return $commandBus;
    }

    public static function createQueryBus(MessageBusRouterPlugin $router): QueryBus
    {
        $queryBus = new QueryBus();
        /** @var Plugin $router */
        $router->attachToMessageBus($queryBus);
        return $queryBus;
    }
}