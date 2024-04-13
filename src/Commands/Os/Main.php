<?php

namespace Bolero\Commands\Os;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "os")]
#[CommandDeclaration(desc: "Display the running operating system name.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $data = $this->application->getOS();
        Console::writeLine($data);
    }
}
