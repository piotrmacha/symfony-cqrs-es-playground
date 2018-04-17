<?php

namespace App\Shared\Api;

class ResourceCommandListBuilder
{
    private $arguments = [];

    public function withPost(string $handler): ResourceCommandListBuilder
    {
        $this->arguments['post'] = $handler;
        return $this;
    }

    public function withPut(string $handler): ResourceCommandListBuilder
    {
        $this->arguments['put'] = $handler;
        return $this;
    }

    public function withDelete(string $handler): ResourceCommandListBuilder
    {
        $this->arguments['delete'] = $handler;
        return $this;
    }

    public function build()
    {
        return new ImmutableResourceCommandList($this->arguments);
    }
}