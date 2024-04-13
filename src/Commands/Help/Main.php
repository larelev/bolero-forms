<?php

namespace Bolero\Forms\Commands\Help;

use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "help")]
#[CommandDeclaration(desc: "Display this help")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $this->application->help();
    }
}
