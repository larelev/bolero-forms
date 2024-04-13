<?php

namespace Bolero\Commands\WatchApplication;

use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "watch")]
#[CommandDeclaration(desc: "Watch the application.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $egg = new Lib($this->application);
        $egg->watch();
    }
}
