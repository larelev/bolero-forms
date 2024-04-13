<?php

namespace Bolero\Forms\Commands;

use Bolero\Forms\CLI\Application;
use Bolero\Forms\Element;

abstract class AbstractCommandLib extends Element
{
    public function __construct(Application $application)
    {
        parent::__construct($application);
    }
}
