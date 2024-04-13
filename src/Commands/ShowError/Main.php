<?php

namespace Bolero\Commands\Error;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "show", subject: "error")]
#[CommandDeclaration(desc: "Display the php error log.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $data = $this->application->getPhpErrorLog();
        Console::writeLine($data);
    }
}
