<?php

namespace Bolero\Commands\ClearCache;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "clear", subject: "cache")]
#[CommandDeclaration(desc: "Clear all cache files.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $data = $this->application->clearRuntime();
        Console::writeLine($data);
    }
}
