<?php

namespace Bolero\Commands\ApplicationTree;

use Bolero\Commands\CommonLib;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "show", subject: "tree")]
#[CommandDeclaration(desc: "Display the tree of the current application.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $egg = new CommonLib($this->application);
        $egg->displayTree(APP_DIR);
    }
}
