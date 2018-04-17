<?php

namespace App\Book\Domain;

use App\Book\Domain\Event\BookCreated;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class Book extends AggregateRoot
{
    private $id;

    private $name;

    private $author;

    protected function aggregateId(): string
    {
        return (string)$this->id;
    }

    /**
     * Apply given event
     */
    protected function apply(AggregateChanged $event): void
    {
        switch (get_class($event)) {
            case BookCreated::class:
                $payload = $event->payload();
                $this->id = $event->aggregateId();
                $this->name = $payload['name'];
                $this->author = $payload['author'];
                break;
        }
    }

    public static function create($id, $name, $author)
    {
        $book = new Book();
        $book->recordThat(BookCreated::occur($id, [
            'name' => $name,
            'author' => $author
        ]));
        return $book;
    }
}