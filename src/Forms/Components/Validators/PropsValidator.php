<?php

namespace Bolero\Forms\Components\Validators;

use Bolero\Forms\Core\Structure;
use ErrorException;
use Exception;
use InvalidArgumentException;

class PropsValidator
{
    protected object $props;
    protected string $structClass;

    public function __construct(object $props, string $structClass)
    {
        $this->props = $props;
        $this->structClass = $structClass;
    }

    public function validate(): ?Structure
    {
        $result = null;

        $structClass = $this->structClass;

        try {
            $result = new $structClass($this->props);
        } catch (Exception $ex) {
            throw new ErrorException("Invalid structure.", 1, 3, __FILE__, __LINE__, $ex);
        }

        return $result;
    }
}