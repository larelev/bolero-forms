<?php

namespace Bolero\Forms\Entity;

use Bolero\Forms\Core\StructureInterface;
use Bolero\Forms\ElementInterface;
use Bolero\Forms\ElementTrait;
use Override;

class Entity implements ElementInterface
{

    use ElementTrait;

    #[Override]
    public static function create(StructureInterface $struct): ElementInterface
    {
        return new self($struct);
    }
}