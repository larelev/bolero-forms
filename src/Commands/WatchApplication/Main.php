<?php

namespace Bolero\Forms\Commands\WatchApplication;

use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "watch")]
#[CommandDeclaration(desc: "Watch the application.")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $egg = new Lib($this->application);
        $egg->watch();

        return 0;
    }
}
