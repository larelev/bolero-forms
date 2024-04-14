<?php

namespace Bolero\Forms\Commands\MakePhar;

use Bolero\Forms\Apps\Egg\PharLib;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "make", subject: "phar")]
#[CommandDeclaration(desc: "Make a phar archive of the current application with files in vendor directory.")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $phar = new PharLib($this->application);
        $phar->makeVendorPhar();

        return 0;
    }
}
