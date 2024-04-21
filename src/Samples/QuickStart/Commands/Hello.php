<?php

namespace Bolero\Forms\Commands;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "say", subject: "hello")]
#[CommandDeclaration(desc: "Say hello.")]
class Hello extends AbstractCommand
{
    public function run(): int
    {
        $data = 'world';
        $data = $this->application->getArgi(2, $data);
        Console::writeLine("Hello %s!", $data);
    }
}
