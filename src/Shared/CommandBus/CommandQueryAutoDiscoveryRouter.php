<?php

namespace App\Shared\CommandBus;

use Prooph\Common\Event\ActionEvent;
use Prooph\ServiceBus\MessageBus;
use Prooph\ServiceBus\Plugin\AbstractPlugin;
use Prooph\ServiceBus\Plugin\Router\MessageBusRouterPlugin;
use Psr\Container\ContainerInterface;

class CommandQueryAutoDiscoveryRouter extends AbstractPlugin implements MessageBusRouterPlugin
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * Handle route action event of a message bus dispatch
     */
    public function onRouteMessage(ActionEvent $actionEvent): void
    {
        $message = (string)$actionEvent->getParam(MessageBus::EVENT_PARAM_MESSAGE_NAME);
        $guessedHandlerName = $message . 'Handler';

        if (!class_exists($guessedHandlerName)) {
            throw new \RuntimeException(
                sprintf('Can not find handler for message "%s". Expected: "%s"', $message, $guessedHandlerName)
            );
        }

        if (!$this->serviceContainer->has($guessedHandlerName)) {
            throw new \RuntimeException(
                sprintf('Handler "%s" exist but is not present in the service container', $guessedHandlerName)
            );
        }

        $handler = $this->serviceContainer->get($guessedHandlerName);
        $actionEvent->setParam(MessageBus::EVENT_PARAM_MESSAGE_HANDLER, $handler);
    }

    public function attachToMessageBus(MessageBus $messageBus): void
    {
        $this->listenerHandlers[] = $messageBus->attach(
            MessageBus::EVENT_DISPATCH,
            [$this, 'onRouteMessage'],
            MessageBus::PRIORITY_ROUTE
        );
    }
}