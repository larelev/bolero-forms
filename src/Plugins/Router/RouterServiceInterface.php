<?php

namespace Bolero\Forms\Plugins\Router;

use Bolero\Forms\Plugins\Route\RouteEntity;
use Bolero\Forms\Plugins\Route\RouteInterface;

interface RouterServiceInterface
{
    public static function findRouteArguments(string $route): ?array;

    public static function findRouteQueryString(string $route): ?string;

    public function findRoute(string &$html): void;

    public function renderRoute(bool $pageFound, string $path, array $query, int $error, int $responseCode, string &$html): void;

    public function doRouting(): ?array;

    public function addRoute(RouteInterface $route): void;

    public function saveRoutes(): bool;

    public function matchRoute(RouteEntity $route): ?array;
}
