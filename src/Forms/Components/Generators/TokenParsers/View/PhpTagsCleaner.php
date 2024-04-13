<?php

namespace Bolero\Forms\Components\Generators\TokenParsers\View;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\CLI\ConsoleOptions;
use Bolero\Forms\Components\Generators\TokenParsers\AbstractTokenParser;

final class PhpTagsCleaner extends AbstractTokenParser
{
    public function do(null|string|array $parameter = null): void
    {
        $phtml = $parameter;

        $re = '/\?>( |\s+?)<\?php/m';
        $phtml = preg_replace($re, "$1", $phtml);

        $re = '/<\?=(\$\w+)\ +\n/m';
        $phtml = preg_replace($re, "<?php echo $1;\n", $phtml);

        $this->result = $phtml;
    }
}
