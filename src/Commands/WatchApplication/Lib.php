<?php

namespace Bolero\Forms\Commands\WatchApplication;

use Bolero\Forms\Commands\AbstractCommandLib;
use Bolero\Forms\Components\FileSystem\Watcher;

class Lib extends AbstractCommandLib
{
    public function watch(): void
    {
        $watcher = new Watcher;

        $watcher->watch(SRC_ROOT, ['phtml', 'php']);
    }
}

