<?php

namespace Bolero\Forms\Core;

use Bolero\Forms\Registry\FrameworkRegistry;
use Bolero\Forms\Registry\PharRegistry;

class Autoloader
{

    /**
     * Registers the autoloader class with the PHP SPL autoloader.
     *
     * @param bool $prepend Prepend the autoloader on the stack instead of appending it.
     */
    public static function register($prepend = false)
    {
        spl_autoload_register(array(new self, 'load'), true, $prepend);
    }

    public static function load($className): void
    {
        $classFilename = FrameworkRegistry::read($className);

        include $classFilename;
    }
}
