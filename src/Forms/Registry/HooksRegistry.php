<?php

namespace Bolero\Forms\Registry;

use Bolero\Forms\IO\Utils;

class HooksRegistry
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function create(): HooksRegistry
    {
        if (self::$instance === null) {
            self::$instance = new HooksRegistry;
        }

        return self::$instance;
    }

    public static function register(): void
    {
        self::create()->_register();
    }

    protected function _register(): void
    {

        $hooks = [];
        $dir_handle = opendir(BOLERO_FORMS_ROOT . HOOKS_PATH);

        while (false !== $filename = readdir($dir_handle)) {
            if ($filename == '.' || $filename == '..') {
                continue;
            }

            array_push($hooks, str_replace(DIRECTORY_SEPARATOR, '_' , HOOKS_PATH . $filename));

            include BOLERO_FORMS_ROOT . HOOKS_PATH . $filename;
        }

        $hooksRegistry = ['Hooks' => $hooks];

        Utils::safeWrite(RUNTIME_DIR . 'HooksRegistry.json',  json_encode($hooksRegistry));
    }
}
