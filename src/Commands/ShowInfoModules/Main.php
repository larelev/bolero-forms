<?php

namespace Bolero\Commands\InfoModules;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;
use Bolero\Forms\Core\PhpInfo;

#[CommandDeclaration(verb: "show", subject: "modules")]
#[CommandDeclaration(desc: "Display the module section of phpinfo() output.")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $info = new PhpInfo;
        $data = $info->getModulesSection(true);
        Console::writeLine($data);
    }
}
