<?php

namespace Bolero\Forms\Components\Generators\TokenParsers\View;

use Bolero\Forms\Components\Generators\TokenParsers\AbstractTokenParser;

final class WhileParser extends AbstractTokenParser
{
    public function do(null|string|array $parameter = null): void
    {
        $re = '/@while ([\w @%&!=\'"+\*\/;\<\-\>\(\)\[\]]+) do/m';
        $subst = '<% while($1) { %>';
        $result = preg_replace($re, $subst, $parameter);

        $this->result = $result;
    }

}
