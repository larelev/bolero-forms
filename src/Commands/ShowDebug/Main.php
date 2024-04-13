<?php

namespace Bolero\Forms\Commands\Debug;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "show", subject: "debug")]
#[CommandDeclaration(desc: "Display the debug log.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $data = $this->application->getDebugLog();
        Console::writeLine($data);
    }
}
