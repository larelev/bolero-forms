<?php

namespace Bolero\Commands\Constants;

use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "show", subject: "constants")]
#[CommandDeclaration(desc: "Display the application constants.")]
class Main extends AbstractCommand
{

    public function run(): void
    {
        $lib = new Lib($this->application);
        $lib->displayConstants();
    }
}
