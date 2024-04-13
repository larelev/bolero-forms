<?php

namespace Bolero\Forms\Commands\Build;

use Bolero\Forms\Commands\AbstractCommandLib;
use Bolero\Forms\Core\Builder;
use Bolero\Forms\IO\Utils;

class Lib extends AbstractCommandLib
{

    public function build(): void
    {
        $application = $this->parent;

        $application->clearRuntime();
        $application->clearLogs();

        $builder = new Builder;
        $builder->describeComponents();
        $builder->prepareRoutedComponents();

        $builder->buildAllRoutes();
    }
}

