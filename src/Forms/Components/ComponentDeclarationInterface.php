<?php

namespace Bolero\Forms\Components;

use Bolero\Forms\ElementInterface;

interface ComponentDeclarationInterface extends ElementInterface
{
    function getType(): string;

    function getName(): string;

    function hasArguments(): bool;

    function getArguments(): ?array;

    function getComposition(): ?ComponentEntity;
}