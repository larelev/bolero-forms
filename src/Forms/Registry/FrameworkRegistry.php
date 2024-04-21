<?php

namespace Bolero\Forms\Registry;

use Bolero\Forms\ElementUtils;
use Bolero\Forms\IO\Utils;

class FrameworkRegistry extends AbstractStaticRegistry
{
    private static ?AbstractRegistryInterface $instance = null;

    public static function reset(): void
    {
        self::$instance = new FrameworkRegistry;
        self::$instance->_setCacheDirectory(RUNTIME_DIR);
        unlink(self::$instance->getCacheFilename());
    }

    public static function getInstance(): AbstractRegistryInterface
    {
        if (self::$instance === null) {
            self::$instance = new FrameworkRegistry;
            self::$instance->_setCacheDirectory(RUNTIME_DIR);
        }

        return self::$instance;
    }

    public static function register(): void
    {
        if (!FrameworkRegistry::uncache(true)) {

            $frameworkFiles = Utils::walkTreeFiltered(BOLERO_FORMS_ROOT, ['php']);

            foreach ($frameworkFiles as $filename) {
                if (
                    $filename === 'bootstrap.php'
                    || str_contains($filename, 'constants.php')
                    || str_contains($filename, 'Autoloader.php')
                ) {
                    continue;
                }

                if (str_contains($filename, 'Interface')) {
                    list($namespace, $interface) = ElementUtils::getInterfaceDefinitionFromFile(BOLERO_FORMS_ROOT . $filename);
                    $fqname = $namespace . '\\' . $interface;
                    FrameworkRegistry::write($fqname, BOLERO_FORMS_ROOT . $filename);
                    continue;
                }

                if (str_contains($filename, 'Trait')) {
                    list($namespace, $trait) = ElementUtils::getTraitDefinitionFromFile(BOLERO_FORMS_ROOT . $filename);
                    $fqname = $namespace . '\\' . $trait;
                    FrameworkRegistry::write($fqname, BOLERO_FORMS_ROOT . $filename);
                    continue;
                }

                list($namespace, $class) = ElementUtils::getClassDefinitionFromFile(BOLERO_FORMS_ROOT . $filename);
                $fqname = $namespace . '\\' . $class;
                if ($class === '') {
                    list($namespace, $function) = ElementUtils::getFunctionDefinitionFromFile(BOLERO_FORMS_ROOT . $filename);
                    $fqname = $namespace . '\\' . $function;
                }
                if ($fqname !== '\\') {
                    FrameworkRegistry::write($fqname, BOLERO_FORMS_ROOT . $filename);
                }
            }

            self::registerUserClasses();

            FrameworkRegistry::cache(true);

        }
    }

    public static function registerUserClasses(): void
    {
        if (!file_exists(SRC_ROOT)) return;

        $sourceFiles = Utils::walkTreeFiltered(SRC_ROOT, ['php']);

        foreach ($sourceFiles as $filename) {
            if (str_contains($filename, 'Interface')) {
                list($namespace, $interface) = ElementUtils::getInterfaceDefinitionFromFile(SRC_ROOT . $filename);
                $fqname = $namespace . '\\' . $interface;
                FrameworkRegistry::write($fqname, SRC_ROOT . $filename);
                continue;
            }

            if (str_contains($filename, 'Trait')) {
                list($namespace, $trait) = ElementUtils::getTraitDefinitionFromFile(SRC_ROOT . $filename);
                $fqname = $namespace . '\\' . $trait;
                FrameworkRegistry::write($fqname, SRC_ROOT . $filename);
                continue;
            }

            list($namespace, $class) = ElementUtils::getClassDefinitionFromFile(SRC_ROOT . $filename);
            $fqname = $namespace . '\\' . $class;
            if ($class === '') {
                list($namespace, $function) = ElementUtils::getFunctionDefinitionFromFile(SRC_ROOT . $filename);
                $fqname = $namespace . '\\' . $function;
            }
            if ($fqname !== '\\') {
                FrameworkRegistry::write($fqname, SRC_ROOT . $filename);
            }
        }
    }
}
