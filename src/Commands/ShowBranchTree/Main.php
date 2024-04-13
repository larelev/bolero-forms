<?php

namespace Bolero\Commands\BranchTree;

use Bolero\Commands\CommonLib;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "show", subject: "branch-tree")]
#[CommandDeclaration(desc: "Display the tree of the Bolero framework master branch.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $dir = 'master' . DIRECTORY_SEPARATOR . 'bolero-master' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'bolero';

        $egg = new CommonLib($this->application);
        $egg->displayTree($dir);
    }
}
