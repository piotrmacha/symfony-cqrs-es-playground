<?php

namespace App\Shared\Api;

interface CommandAwareResource
{
    public static function commandClassList(): ImmutableResourceCommandList;
}