<?php

namespace Bolero\Forms\Components\Generators\TokenParsers\View;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Components\Generators\TokenParsers\AbstractTokenParser;

final class ValuesParser extends AbstractTokenParser
{
    public function do(null|string|array|object $parameter = null): void
    {

        $text = '';
        if($parameter !== null && is_array($parameter)) {
            $text = $parameter['html'];
            $this->useVariables = $parameter['useVariables'];
        }

        $re = '/%([\w]+)/m';
        preg_match_all($re, $text, $matches, PREG_SET_ORDER, 0);

        foreach ($matches as $match) {
            $useVar = $match[1];
            $this->useVariables[$useVar] = '$' . $useVar;

            $text = str_replace($match[0], '$' . $useVar, $text);
        }

        $this->result = $text;

    }

}