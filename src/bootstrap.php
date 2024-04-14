<?php

use Bolero\Forms\Core\Autoloader;
use Bolero\Forms\JavaScripts\AjilBuilder;
use Bolero\Forms\Registry\FrameworkRegistry;
use Bolero\Forms\Registry\HooksRegistry;
use Bolero\Forms\Registry\PharRegistry;

define('LIBDIR_SEPARATOR', Phar::running() ? '_' : DIRECTORY_SEPARATOR);
define('FRAMEWORK_PATH',  dirname(__FILE__) . LIBDIR_SEPARATOR . 'Forms' . LIBDIR_SEPARATOR);
const HOOKS_PATH = 'Hooks' . LIBDIR_SEPARATOR;

include FRAMEWORK_PATH . 'Core' . LIBDIR_SEPARATOR . 'constants.php';
include FRAMEWORK_PATH . 'Core' . LIBDIR_SEPARATOR . 'Autoloader.php';
include FRAMEWORK_PATH . 'Io' . LIBDIR_SEPARATOR . 'Utils.php';
include FRAMEWORK_PATH . 'Utils' . LIBDIR_SEPARATOR . 'TextUtils.php';
include FRAMEWORK_PATH . 'ElementTrait.php';
include FRAMEWORK_PATH . 'ElementUtils.php';
include FRAMEWORK_PATH . 'Registry' . LIBDIR_SEPARATOR . 'AbstractRegistryInterface.php';
include FRAMEWORK_PATH . 'Registry' . LIBDIR_SEPARATOR . 'StaticRegistryInterface.php';
include FRAMEWORK_PATH . 'Registry' . LIBDIR_SEPARATOR . 'AbstractRegistry.php';
include FRAMEWORK_PATH . 'Registry' . LIBDIR_SEPARATOR . 'AbstractStaticRegistry.php';
include FRAMEWORK_PATH . 'Registry' . LIBDIR_SEPARATOR . 'FrameworkRegistry.php';
include FRAMEWORK_PATH . 'Registry' . LIBDIR_SEPARATOR . 'HooksRegistry.php';

HooksRegistry::register();

FrameworkRegistry::register();

Autoloader::register();

if (IS_WEB_APP) {
    AjilBuilder::build();
}
