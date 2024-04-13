<?php

namespace Bolero\Commands\MakePhar;

use Bolero\Apps\Egg\PharLib;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "make", subject: "phar")]
#[CommandDeclaration(desc: "Make a phar archive of the current application with files in vendor directory.")]
#[CommandDeclaration(isPhar: IS_PHAR_APP)]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $phar = new PharLib($this->application);
        $phar->makeVendorPhar();
    }
}
