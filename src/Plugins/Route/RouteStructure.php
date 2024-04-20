<?php

namespace Bolero\Forms\Plugins\Route;

use Bolero\Forms\Core\Structure;

class RouteStructure extends Structure
{
    public string $uid = '';
    public string $method = '';
    public string $rule = '';
    public string $normalized = '';
    public string $redirect = '';
    public string $translation = '';
    public string $error = '';
    public string $exact = '';
    public array $middlewares = [];
}