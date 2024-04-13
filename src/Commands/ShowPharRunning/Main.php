<?php

namespace Bolero\Forms\Commands\Running;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;
use Phar;

#[CommandDeclaration(verb: "show", subject: "phar-running")]
#[CommandDeclaration(desc: "Show Phar::running() output")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        Console::writeLine(Phar::running());
    }
}
