<?php

namespace Bolero\Forms\Components\Generators\TokenParsers;

use Bolero\Forms\Registry\ComponentRegistry;
use Bolero\Forms\Registry\FrameworkRegistry;

final class UsesAsParser extends AbstractTokenParser
{
    public function do(null|string|array $parameter = null): void
    {
        $compNamespace = $this->component->getNamespace();

        $re = '/use[ ]+([A-Za-z0-9\\\\]*\\\\)?([A-Za-z0-9]*)[ ]+as[ ]+([A-Za-z0-9]*);/m';

        preg_match_all($re, $this->html, $matches, PREG_SET_ORDER, 0);

        foreach ($matches as $match) {
            $componentNamespace = trim($match[1], '\\');
            $componentFunction = $match[2];
            $componentAlias = $match[3];

            $componentNamespace = ($componentNamespace === '') ? $compNamespace : $componentNamespace;
            $fqFunctionName = $componentNamespace . '\\' . $componentFunction;
            $this->useTypes[] = $fqFunctionName;

            ComponentRegistry::write($componentAlias, $fqFunctionName);
        }
    }

}