<?php

namespace App\Shared\Api;

interface CommandAwareResource
{
    public function commandList(): ImmutableResourceCommandList;
}