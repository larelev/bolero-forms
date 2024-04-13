<?php

namespace Bolero\Forms\Commands\RequireMaster;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "wget", subject: "master-branch")]
#[CommandDeclaration(desc: "Download the ZIP file of the master branch of Bolero\Forms framework.")]
#[CommandDeclaration(isPhar: IS_PHAR_APP)]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $egg = new Lib($this->application);
        $result = $egg->requireMaster();

        Console::writeLine($result->tree);
    }
}
