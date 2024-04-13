<?php

namespace Bolero\Forms\Components\Generators\TokenParsers\View;

use Bolero\Forms\Components\Generators\TokenParsers\AbstractTokenParser;

final class PhpTagsParser extends AbstractTokenParser
{
    public function do(null|string|array $parameter = null): void
    {
        $phtml = $parameter;

        $re = '/(<%)(( +|\s+)?)/m';
        $phtml = preg_replace($re, '<?php' . "$2", $phtml);

        $re = '/%>/m';
        $phtml = preg_replace($re, "?>", $phtml);

        $this->result = $phtml;
    }
}
