<?php

namespace Bolero\Forms\Commands\RequireMaster;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Commands\AbstractCommandLib;
use Bolero\Forms\IO\Utils;
use Bolero\Forms\Web\Curl;

class Lib extends AbstractCommandLib
{

    public function requireMaster(): object
    {

        $libRoot = CACHE_DIR . 'archive' . DIRECTORY_SEPARATOR;

        Console::writeLine($libRoot);

        if (!file_exists($libRoot)) {
            mkdir($libRoot);
        }

        $master = $libRoot . 'main';
        $filename = $master . '.zip';
        $ephectDir = $master . DIRECTORY_SEPARATOR . 'framework-main' . DIRECTORY_SEPARATOR . 'Bolero\Forms' . DIRECTORY_SEPARATOR . 'Framework' . DIRECTORY_SEPARATOR;

        $tree = [];

        if (!file_exists($filename)) {
            Console::writeLine('Downloading ephect github main');
            $curl = new Curl();
            [$code, $header, $content] = $curl->request('https://codeload.github.com/ephect-io/framework/zip/main');

            file_put_contents($filename, $content);
        }

        if (file_exists($filename)) {
            Console::writeLine('Inflating ephect master archive');
            $zip = new Zip();
            $zip->inflate($filename);
        }

        if (file_exists($filename)) {
            $tree = Utils::walkTreeFiltered($ephectDir, ['php']);
        }

        unlink($filename);
        $result = ['path' => $ephectDir, 'tree' => $tree];

        return (object) $result;
    }
}




