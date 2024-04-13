<?php

namespace Bolero\Forms\Components\Generators\TokenParsers\View;

use Bolero\Forms\Components\Generators\TokenParsers\AbstractTokenParser;

final class ForeachParser extends AbstractTokenParser
{
    public function do(null|string|array $parameter = null): void
    {
        $re = '/@for +((%[\w\->()\[\]]+) +as +(%\w+( +=> +%\w+)?)) +do/m';
        $subst = "<% foreach($2 as $3) { %>";
        $result = preg_replace($re, $subst, $parameter);

        $this->result = $result;
    }

}
