<?php

namespace Bolero\Forms\Commands\BranchTree;

use Bolero\Forms\Commands\CommonLib;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "show", subject: "branch-tree")]
#[CommandDeclaration(desc: "Display the tree of the Bolero\Forms framework master branch.")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $dir = 'master' . DIRECTORY_SEPARATOR . 'ephect-master' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'ephect';

        $egg = new CommonLib($this->application);
        $egg->displayTree($dir);

        return 0;
    }
}
