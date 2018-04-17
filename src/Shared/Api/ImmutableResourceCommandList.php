<?php

namespace App\Shared\Api;

class ImmutableResourceCommandList
{
    private $mapping = [];

    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    public function has(string $method): bool
    {
        return array_key_exists($method, $this->mapping);
    }

    public function get(string $method): string
    {
        if (!$this->has($method)) {
            throw new \RuntimeException(sprintf('Can not find command for method "%s"', $method));
        }

        return $this->mapping[$method];
    }
}