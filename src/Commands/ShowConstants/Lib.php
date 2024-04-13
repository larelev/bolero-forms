<?php

namespace Bolero\Commands\Constants;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\CLI\ConsoleColors;
use Bolero\Forms\Commands\AbstractCommandLib;
use Throwable;

class Lib extends AbstractCommandLib
{

    public function displayConstants(): array
    {
        try {
            $constants = [];
            $constants['APP_NAME'] = APP_NAME;
            $constants['APP_CWD'] = APP_CWD;
            $constants['SCRIPT_ROOT'] = SCRIPT_ROOT;
            $constants['SRC_ROOT'] = SRC_ROOT;
            $constants['SITE_ROOT'] = SITE_ROOT;
            $constants['IS_PHAR_APP'] = IS_PHAR_APP ? 'TRUE' : 'FALSE';
            $constants['BOLERO_ROOT'] = BOLERO_ROOT;

            // $constants['BOLERO_VENDOR_SRC'] = BOLERO_VENDOR_SRC;
            // $constants['BOLERO_VENDOR_LIB'] = BOLERO_VENDOR_LIB;
            // $constants['BOLERO_VENDOR_APPS'] = BOLERO_VENDOR_APPS;

            if (APP_NAME !== 'egg') {
                $constants['APP_ROOT'] = APP_ROOT;
                $constants['APP_SCRIPTS'] = APP_SCRIPTS;
                $constants['APP_BUSINESS'] = APP_BUSINESS;
                $constants['MODEL_ROOT'] = MODEL_ROOT;
                $constants['VIEW_ROOT'] = VIEW_ROOT;
                $constants['CONTROLLER_ROOT'] = CONTROLLER_ROOT;
                $constants['REST_ROOT'] = REST_ROOT;
                $constants['APP_DATA'] = APP_DATA;
                $constants['CACHE_DIR'] = CACHE_DIR;
            }
            $constants['LOG_PATH'] = LOG_PATH;
            $constants['DEBUG_LOG'] = DEBUG_LOG;
            $constants['ERROR_LOG'] = ERROR_LOG;

            Console::writeLine('Application constants are :');
            foreach ($constants as $key => $value) {
                Console::writeLine(ConsoleColors::getColoredString($key, ConsoleColors::CYAN) . ' => ' . ConsoleColors::getColoredString($value, ConsoleColors::BLUE));
            }

            return $constants;
        } catch (Throwable $ex) {
            Console::error($ex);

            return [];
        }
    }
}

