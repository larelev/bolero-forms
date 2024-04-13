<?php

namespace Bolero\Commands\MakeMasterPhar;

use Bolero\Apps\Egg\PharLib;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "make", subject: "master-phar")]
#[CommandDeclaration(desc: "Make a phar archive of the current application with files from the master repository.")]
#[CommandDeclaration(isPhar: IS_PHAR_APP)]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $phar = new PharLib($this->application);
        $phar->makeMasterPhar();
    }
}
