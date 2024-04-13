<?php

namespace Bolero\Forms\Commands\ClearLogs;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "clear", subject: "logs")]
#[CommandDeclaration(desc: "Clear all logs.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $data = $this->application->clearLogs();
        Console::writeLine($data);
    }
}
