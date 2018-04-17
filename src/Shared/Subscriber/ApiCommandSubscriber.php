<?php

namespace App\Shared\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Book\Application\Dto\Book;
use App\Book\Command\CreateBook\CreateBookCommand;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\MessageDispatchException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiCommandSubscriber implements EventSubscriberInterface
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

        if (!in_array($method, ['POST', 'PUT', 'PATH'])) {
            // we don't want to look for a command to queries
            return;
        }

        $commandClass = $this->getCommand($result, $method);
        $command = new $commandClass($result);

        try {
            $this->commandBus->dispatch($command);
        } catch (MessageDispatchException $exception) {
            // API Platform shows only the newest exception message, so we need to rethrow the second in order
            $previous = $exception->getPrevious();
            if ($previous instanceof \Exception) {
                throw $previous;
            }
            throw $exception;
        }
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