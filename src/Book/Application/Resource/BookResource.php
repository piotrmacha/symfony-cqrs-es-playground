<?php

namespace App\Book\Application\Resource;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Book\Command\CreateBook\CreateBookCommand;
use App\Book\Query\BookCollection\BookCollectionQuery;
use App\Book\Query\BookItem\BookItemQuery;
use App\Shared\Api\CollectionQueryAwareResource;
use App\Shared\Api\CommandAwareResource;
use App\Shared\Api\ImmutableResourceCommandList;
use App\Shared\Api\ItemQueryAwareResource;
use App\Shared\Api\ResourceCommandListBuilder;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class BookResource implements ItemQueryAwareResource, CollectionQueryAwareResource, CommandAwareResource
{
    /**
     * @ApiProperty(identifier=true)
     * @Groups("read")
     * @var UuidInterface
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $author;

    public static function itemQueryClass(): string
    {
        return BookItemQuery::class;
    }

    public static function collectionQueryClass(): string
    {
        return BookCollectionQuery::class;
    }

    public static function commandClassList(): ImmutableResourceCommandList
    {
        return (new ResourceCommandListBuilder())
            ->withPost(CreateBookCommand::class)
            ->build();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }
}