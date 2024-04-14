<?php

namespace Ephect\Commands\{{CommandNamespace}};

use Bolero\Forms\Commands\AbstractCommand;
use Bolero\Forms\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration({{CommandAttributes}})]
#[CommandDeclaration(desc: "{{Description}}")]
class Main extends AbstractCommand
{
    public function run(): void
    {
        {{GetArgs}}
        $lib = new Lib($this->application);
        $lib->{{MethodName}}({{SetArgs}});
    }
}
