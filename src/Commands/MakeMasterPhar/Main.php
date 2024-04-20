<?php

namespace Bolero\Forms\Commands\MakeMasterPhar;

use Bolero\Forms\Apps\Egg\PharLib;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "make", subject: "master-phar")]
#[CommandDeclaration(desc: "Make a phar archive of the current application with files from the master repository.")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $phar = new PharLib($this->application);
        $phar->makeMasterPhar();

        return 0;
    }
}
