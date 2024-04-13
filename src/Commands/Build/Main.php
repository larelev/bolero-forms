<?php

namespace Bolero\Forms\Commands\Build;

use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "build")]
#[CommandDeclaration(desc: "Build the application.")]
class Main extends AbstractCommand
{
    public function run(): void
    {

        $egg = new Lib($this->application);
        $egg->build();
    }
}
