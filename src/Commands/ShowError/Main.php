<?php

namespace Bolero\Forms\Commands\Error;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "show", subject: "error")]
#[CommandDeclaration(desc: "Display the php error log.")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $data = $this->application->getPhpErrorLog();
        Console::writeLine($data);

        return 0;
    }
}
