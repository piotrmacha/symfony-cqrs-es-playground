<?php

namespace App\Shared\CommandBus;

use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Plugin;
use Prooph\ServiceBus\Plugin\Router\MessageBusRouterPlugin;

class CommandBusFactory
{
    public static function createCommandBus(MessageBusRouterPlugin $router): CommandBus
    {
        $commandBus = new CommandBus();
        /** @var Plugin $router */
        $router->attachToMessageBus($commandBus);
        return $commandBus;
    }
}