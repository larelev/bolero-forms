<?php

namespace Bolero\Forms\Plugins\Route\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_FUNCTION)]
class RouteMiddleware
{
    public function __construct(
    )
    {
    }
}