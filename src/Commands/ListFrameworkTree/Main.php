<?php

namespace Bolero\Forms\Commands\FrameworkTree;

use Bolero\Forms\Commands\CommonLib;
use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "list", subject: "framework")]
#[CommandDeclaration(desc: "Display the tree of the Bolero\Forms framework.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        Console::writeLine(EPHECT_ROOT);
        $egg = new CommonLib($this->application);
        $egg->displayTree(EPHECT_ROOT);
    }
}
