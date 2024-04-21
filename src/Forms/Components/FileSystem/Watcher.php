<?php
declare(ticks=1);

namespace Bolero\Forms\Components\FileSystem;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\IO\Utils;
use function Bolero\Forms\Hooks\useInterval;

class Watcher
{
    public function watch(string $directory, array $filter): void
    {
        $mtimes = [];

        useInterval(function () use ($directory, $filter, &$mtimes) {
            $files = $this->_listFiles($directory, $filter);

            foreach ($files as $filename) {

                $mtime = filemtime($directory . $filename);

                if (isset($mtimes[$filename]) && $mtimes[$filename] < $mtime) {
                    Console::writeLine('File "%s" was modified', $directory . $filename);
                }
                $mtimes[$filename] = $mtime;

            }

        }, 100);

        while (true) usleep(1);

    }

    private function _listFiles(string $directory, array $filter): array
    {
        return Utils::walkTreeFiltered($directory, $filter);
    }
}