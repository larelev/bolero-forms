<?php

namespace Bolero\Forms\Commands\Serve;

use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "serve")]
#[CommandDeclaration(desc: "Launch PHP embedded server on available port starting from the one in config.")]
class Main extends AbstractCommand
{

    public function run(): int
    {
        $lib = new Lib($this->application);
        $lib->serve();

        return 0;
    }
}
