<?php

namespace Bolero\Commands\Arguments;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "show", subject: "arguments")]
#[CommandDeclaration(desc: "Show the application arguments.")]
class Main extends AbstractCommand
{

    public function run(): void
    {
        $data = ['argv' => $this->application->getArgv(), 'argc' => $this->application->getArgc()];
        Console::writeLine($data);
    }
}
