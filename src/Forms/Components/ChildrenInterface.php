<?php

namespace Bolero\Forms\Components;

use Bolero\Forms\ElementInterface;
use Bolero\Forms\Tree\TreeInterface;

interface ChildrenInterface extends TreeInterface, ElementInterface
{
    function getAttributes(): string;

    function getAttribute(string $attribute): string|bool|null;

    function parentProps(): array|object;

    function props(): array|object;

    function getAllProps(): array|object;

    function render(): void;

    function getName(): string;
}