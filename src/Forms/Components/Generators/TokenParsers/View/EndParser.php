<?php

namespace Bolero\Forms\Components\Generators\TokenParsers\View;

use Bolero\Forms\Components\Generators\TokenParsers\AbstractTokenParser;

final class EndParser extends AbstractTokenParser
{
    public function do(null|string|array $parameter = null): void
    {
        $re = '/@done/m';
        $subst = '<% } %>';
        $result = preg_replace($re, $subst, $parameter, 1);

        $this->result = $result;
    }

}
