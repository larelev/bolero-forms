<?php

namespace Bolero\Forms\Commands\MakeQuickStart;

use Bolero\Forms\Commands\CommonLib;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "make", subject: "quickstart")]
#[CommandDeclaration(desc: "Create the quickstart application tree.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $egg = new CommonLib($this->application);
        $egg->createCommonTrees();

        $lib = new Lib($this->application);
        $lib->createQuickstart();
    }
}
