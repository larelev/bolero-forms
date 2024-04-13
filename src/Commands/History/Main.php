<?php

namespace Bolero\Forms\Commands\History;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "history")]
#[CommandDeclaration(desc: "Display the commands history.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $history = readline_list_history();
        Console::writeLine($history);
    }
}
