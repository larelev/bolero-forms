<?php

namespace Bolero\Forms\Commands\Name;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "name")]
#[CommandDeclaration(desc: "Display the running application name.")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $data = $this->application->getName();
        Console::writeLine($data);

        return 0;
    }
}
