<?php

namespace Bolero\Commands\Title;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "title")]
#[CommandDeclaration(desc: "Display the running application title.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $data = $this->application->getTitle();
        Console::writeLine($data);
    }
}
