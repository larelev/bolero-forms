<?php

namespace Bolero\Forms\Commands\Os;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "os")]
#[CommandDeclaration(desc: "Display the running operating system name.")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $data = $this->application->getOS();
        Console::writeLine($data);

        return 0;
    }
}
