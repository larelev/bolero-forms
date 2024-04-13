<?php

namespace Bolero\Commands;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "say", subject: "hello")]
#[CommandDeclaration(desc: "Say hello.")]
class Hello extends AbstractCommand
{
    public function run(): void
    {
        $data = 'world';
        if($this->application->getArgc() > 2) {
            $data = $this->application->getArgv()[2];
        }
        Console::writeLine("Hello %s!", $data);
    }
}
