<?php

namespace App\Shared\Api;

interface ItemQueryAwareResource
{
    public static function itemQueryClass(): string;
}