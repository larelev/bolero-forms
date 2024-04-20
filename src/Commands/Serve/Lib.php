<?php

namespace Bolero\Forms\Commands\Serve;

use Bolero\Forms\Commands\CommonLib;
use Bolero\Forms\CLI\Console;
use Bolero\Forms\CLI\ConsoleColors;
use Bolero\Forms\CLI\System\Command;
use Bolero\Forms\Commands\AbstractCommandLib;
use Bolero\Forms\IO\Utils;

class Lib extends AbstractCommandLib
{

    public function Serve(): void
    {
        $egg = new CommonLib($this->parent);
        $port = $this->getPort();

        Utils::safeWrite(CONFIG_DIR . 'dev_port', $port);

        $cmd = new Command();
        $php = $cmd->which('php');

        Console::writeLine('PHP is %s', ConsoleColors::getColoredString($php, ConsoleColors::RED));
        Console::writeLine('Port is %s', ConsoleColors::getColoredString($port, ConsoleColors::RED));
        $cmd->execute($php, '-S', "localhost:$port", '-t', 'public');
        Console::writeLine("Serving the application locally ...");
    }

    public function getPort($default = 8000): int
    {
        $port = $default;

        if ($this->parent->getArgc() > 2) {
            $customPort = $this->parent->getArgi(2);

            $cleanPort = preg_replace('/([\d]+)/', '$1', $customPort);

            if ($cleanPort !== $customPort) {
                $customPort = $port;
            }

            $port = $customPort;
        }

        return $port;
    }
}

