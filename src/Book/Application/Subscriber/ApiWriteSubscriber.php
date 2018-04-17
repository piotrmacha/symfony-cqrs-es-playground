<?php

namespace App\Book\Application\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Book\Application\Dto\Book;
use App\Book\Command\CreateBook\CreateBookCommand;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiWriteSubscriber implements EventSubscriberInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                'write',
                EventPriorities::PRE_WRITE
            ]
        ];
    }

    public function write(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        $commandClass = $this->getCommand($result, $method);
        $command = new $commandClass($result);

        $this->commandBus->dispatch($command);
    }

    private function getCommand($result, string $method)
    {
        $commands = [
            Book::class => [
                'post' => CreateBookCommand::class
            ]
        ];

        $class = get_class($result);

        if (!array_key_exists($class, $commands)) {
            throw new \RuntimeException('Command for given resource does not exist');
        }

        $method = strtolower($method);
        $methods = $commands[$class];
        if (!array_key_exists($method, $methods)) {
            throw new \RuntimeException('Command for given resource does not exist');
        }

        return $methods[$method];
    }
}