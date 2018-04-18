<?php

namespace App\Shared\Api;

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use App\Shared\Collection\PaginatedCollection;

class PaginatedResponse implements \Iterator, PaginatorInterface
{
    /**
     * @var PaginatedCollection
     */
    private $collection;
    /**
     * @var int
     */
    private $page;
    /**
     * @var array
     */
    private $items;

    /**
     * PaginatedResponse constructor.
     * @param PaginatedCollection $collection
     * @param int $page
     */
    public function __construct(PaginatedCollection $collection, int $page)
    {
        $this->collection = $collection;
        $this->page = $page;
        $this->items = $collection->fetch($page);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Gets last page.
     *
     * @return float
     */
    public function getLastPage(): float
    {
        return (float)$this->collection->pages();
    }

    /**
     * Gets the number of items in the whole collection.
     *
     * @return float
     */
    public function getTotalItems(): float
    {
        return (float)$this->collection->count();
    }

    /**
     * Gets the current page number.
     *
     * @return float
     */
    public function getCurrentPage(): float
    {
        return (float)$this->page;
    }

    /**
     * Gets the number of items by page.
     *
     * @return float
     */
    public function getItemsPerPage(): float
    {
        return (float)$this->collection->itemsPerPage();
    }


    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        next($this->items);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->key() !== null;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->items);
    }
}