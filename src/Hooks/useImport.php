<?php

namespace Bolero\Forms\Hooks;

function useImport(string $export, string $from)
{
    return "import { $export } from $from" . PHP_EOL;
}
