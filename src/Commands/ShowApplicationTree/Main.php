<?php

namespace Bolero\Forms\Commands\ApplicationTree;

use Bolero\Forms\Commands\CommonLib;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "show", subject: "tree")]
#[CommandDeclaration(desc: "Display the tree of the current application.")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $egg = new CommonLib($this->application);
        $egg->displayTree(APP_DIR);
    }
}
