<?php

namespace Bolero\Forms\Commands\ShowConnections;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;
use Bolero\Forms\Registry\StateRegistry;

#[CommandDeclaration(verb: "show", subject: "connections")]
#[CommandDeclaration(desc: "Display the data connections registered.")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $data = StateRegistry::item('connections');
        Console::writeLine($data);

        return 0;
    }
}
