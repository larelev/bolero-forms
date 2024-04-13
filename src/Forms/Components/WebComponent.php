<?php

namespace Bolero\Forms\Components;

use Bolero\Forms\IO\Utils;
use Bolero\Forms\Registry\ComponentRegistry;
use Bolero\Forms\Registry\WebComponentRegistry;

class WebComponent extends AbstractFileComponent
{

    public function makeComponent(string $filename, string &$html): void
    {
        $info = (object) pathinfo($filename);
        $namespace = CONFIG_NAMESPACE;
        $function = $info->filename;

        $html = <<< COMPONENT
        <?php

        namespace $namespace;

        use function Bolero\Forms\Hooks\useEffect;

        function $function(\$slot) {

        return (<<< HTML
        <WebComponent>
        $html
        </WebComponent>
        HTML);
        }
        COMPONENT;

        Utils::safeWrite(COPY_DIR . $filename, $html);

    }

    public function analyse(): void
    {
        parent::analyse();

        WebComponentRegistry::write($this->getFullyQualifiedFunction(), $this->getSourceFilename());
        ComponentRegistry::safeWrite($this->getFunction(), $this->getFullyQualifiedFunction());
        ComponentRegistry::cache();
    }

    public function parse(): void
    {
        parent::parse();
        $this->cacheHtml();
    }

}
