<?php

namespace Bolero\Forms\WebComponents;

use Bolero\Forms\ElementInterface;

interface ManifestEntityInterface extends ElementInterface
{
    public function getTag(): string;
    public function getClassName(): string;
    public function getEntrypoint(): string;
    public function getArguments(): array;

}