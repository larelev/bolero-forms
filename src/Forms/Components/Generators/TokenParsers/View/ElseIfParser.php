<?php

namespace Bolero\Forms\Components\Generators\TokenParsers\View;

use Bolero\Forms\Components\Generators\TokenParsers\AbstractTokenParser;

final class ElseIfParser extends AbstractTokenParser
{
    public function do(null|string|array $parameter = null): void
    {
        $re = '/@else *if +(([\w @%&!=\'"+\*\/;\<\-\>\(\)\[\]]+)) +do/m';
        $subst = '<%} elseif ($1) {%>';
        $result = preg_replace($re, $subst, $parameter);

        $this->result = $result;
    }

}
