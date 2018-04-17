<?php

namespace App\Shared\CommandBus;

use App\Book\Command\CreateBook\CreateBookCommand;
use App\Book\Command\CreateBook\CreateBookCommandHandler;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Plugin;
use Prooph\ServiceBus\Plugin\Router\MessageBusRouterPlugin;

class CommandBusFactory
{
    private static $mapping = [
        CreateBookCommand::class => CreateBookCommandHandler::class
    ];

    public static function createCommandBus(MessageBusRouterPlugin $router): CommandBus
    {
        $commandBus = new CommandBus();
        /** @var Plugin $router */
        $router->attachToMessageBus($commandBus);
        return $commandBus;
    }
}