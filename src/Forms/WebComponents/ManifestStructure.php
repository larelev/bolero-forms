<?php

namespace Bolero\Forms\WebComponents;

use Bolero\Forms\Core\Structure;

class ManifestStructure extends Structure
{
    public string $tag = '';
    public string $class = '';
    public string $entrypoint = '';
    public array $arguments = [];
}
