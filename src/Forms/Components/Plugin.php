<?php

namespace Bolero\Forms\Components;

use Bolero\Forms\ElementUtils;
use Bolero\Forms\IO\Utils;
use Bolero\Forms\Registry\ComponentRegistry;
use Bolero\Forms\Registry\PluginRegistry;

class Plugin extends AbstractFileComponent implements FileComponentInterface
{

    public function load(?string $filename = null): bool
    {
        $this->filename = $filename ?: '';

        $this->code = Utils::safeRead(PLUGINS_ROOT . $this->filename);

        [$this->namespace, $this->function, $this->bodyStartsAt] = ElementUtils::getFunctionDefinition($this->code);
        return $this->code !== null;
    }

    public function makeComponent(string $filename, string &$html): void
    {
    }

    public function analyse(): void
    {
        parent::analyse();

        PluginRegistry::write($this->getFullyQualifiedFunction(), $this->getSourceFilename());
        ComponentRegistry::safeWrite($this->getFunction(), $this->getFullyQualifiedFunction());
    }

    public function parse(): void
    {
        parent::parse();

        $this->cacheHtml();
    }

}
