<?php

namespace App\Shared\CommandBus;

use App\Book\Command\CreateBook\CreateBookCommand;
use App\Book\Command\CreateBook\CreateBookCommandHandler;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Psr\Container\ContainerInterface;

class CommandBusFactory
{
    private static $mapping = [
        CreateBookCommand::class => CreateBookCommandHandler::class
    ];

    public static function createCommandBus(ContainerInterface $serviceContainer): CommandBus
    {
        $commandBus = new CommandBus();

        $router = new CommandRouter();
        foreach (self::$mapping as $command => $handler) {
            $router->route($command)->to($serviceContainer->get($handler));
        }
        $router->attachToMessageBus($commandBus);

        return $commandBus;
    }
}