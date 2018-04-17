<?php

namespace App\Shared\Api;

interface CollectionQueryAwareResource
{
    public static function collectionQueryClass(): string;
}