<?php

namespace Bolero\Forms\Components\Generators\TokenParsers\View;

use Bolero\Forms\Components\Generators\TokenParsers\AbstractTokenParser;

final class OperationParser extends AbstractTokenParser
{
    public function do(null|string|array $parameter = null): void
    {
        $re = '/@op +(.*)$/m';
        $subst = "<% $1; %>";
        $result = preg_replace($re, $subst, $parameter);

        $this->result = $result;
    }

}
