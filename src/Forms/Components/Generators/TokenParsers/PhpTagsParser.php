<?php

namespace Bolero\Forms\Components\Generators\TokenParsers;

final class PhpTagsParser extends AbstractTokenParser
{
    public function do(null|string|array|object $parameter = null): void
    {
        $re = '/({\?)/u';
        $this->html = preg_replace($re, '<?php', $this->html);
                
        $re = '/(\?})/u';
        $this->html = preg_replace($re, '?> ', $this->html);
    }
}
