<?php

namespace Bolero\Forms\Commands\MakeCommand;

use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "make", subject: "command")]
#[CommandDeclaration(desc: "Create the base tree of a command.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $lib = new Lib($this->application);
        $lib->createCommandBase();
        $this->application->clearRuntime();
    }
}
