<?php

namespace Bolero\Forms\Components;

use Bolero\Forms\IO\Utils;
use Bolero\Forms\Registry\ComponentRegistry;

class Component extends AbstractFileComponent implements FileComponentInterface
{

    public function makeComponent(string $filename, string &$html): void
    {
        $info = (object)pathinfo($filename);
        $namespace = CONFIG_NAMESPACE;
        $function = $info->filename;

        $html = <<< COMPONENT
        <?php

        namespace $namespace;

        function $function() {
        return (<<< HTML
        $html
        HTML);
        }
        COMPONENT;

        Utils::safeWrite(COPY_DIR . $filename, $html);
    }

    public function parse(): void
    {
        parent::parse();

        $this->cacheHtml();
    }

    public function analyse(): void
    {
        parent::analyse();

        ComponentRegistry::write($this->getFullyQualifiedFunction(), $this->getSourceFilename());
        ComponentRegistry::safeWrite($this->getFunction(), $this->getFullyQualifiedFunction());
    }

}
