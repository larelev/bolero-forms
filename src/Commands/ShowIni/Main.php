<?php

namespace Bolero\Forms\Commands\Ini;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;
use Bolero\Forms\Registry\StateRegistry;

#[CommandDeclaration(verb: "show", subject: "ini")]
#[CommandDeclaration(desc: "Display the ini file if exists")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        $this->application->loadInFile();
        $data = StateRegistry::item('ini');
        Console::writeLine($data);    }
}
