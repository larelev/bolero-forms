<?php

namespace Bolero\Commands\FrameworkTree;

use Bolero\Commands\CommonLib;
use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "list", subject: "framework")]
#[CommandDeclaration(desc: "Display the tree of the Bolero framework.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        Console::writeLine(BOLERO_ROOT);
        $egg = new CommonLib($this->application);
        $egg->displayTree(BOLERO_ROOT);
    }
}
