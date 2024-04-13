<?php

namespace Bolero\Commands\MakeSkeleton;

use Bolero\Commands\CommonLib;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "make", subject: "skeleton")]
#[CommandDeclaration(desc: "Create the skeleton application tree.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $egg = new CommonLib($this->application);
        $egg->createCommonTrees();
        $lib = new Lib($this->application);
        $lib->makeSkeleton();
    }
}
