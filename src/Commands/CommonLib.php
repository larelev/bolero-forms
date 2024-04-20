<?php

namespace Bolero\Forms\Commands;

use Bolero\Forms\CLI\Application;
use Bolero\Forms\CLI\Console;
use Bolero\Forms\Element;
use Bolero\Forms\IO\Utils;

class CommonLib extends Element
{

    /**
     * Constructor
     */
    public function __construct(Application $parent)
    {
        parent::__construct($parent);
    }

    public function createCommonTrees(): void
    {
        $common = BOLERO_FORMS_ROOT . 'Samples' . DIRECTORY_SEPARATOR . 'Common';
        $src_dir = $common . DIRECTORY_SEPARATOR . 'config';

        Utils::safeMkDir(CONFIG_DIR);
        $destDir = realpath(CONFIG_DIR);

        $tree = Utils::walkTreeFiltered($src_dir);

        foreach ($tree as $filePath) {
            Utils::safeWrite($destDir . $filePath, '');
            copy($src_dir . $filePath, $destDir . $filePath);
        }

        $src_dir = $common . DIRECTORY_SEPARATOR . 'public';

        Utils::safeMkDir(CONFIG_DOCROOT);
        $destDir = realpath(CONFIG_DOCROOT);

        $tree = Utils::walkTreeFiltered($src_dir);

        foreach ($tree as $filePath) {
            Utils::safeWrite($destDir . $filePath, '');
            copy($src_dir . $filePath, $destDir . $filePath);
        }
    }

    public function requireTree(string $treePath): object
    {
        $tree = Utils::walkTreeFiltered($treePath, ['php']);
        $result = ['path' => $treePath, 'tree' => $tree];

        return (object)$result;
    }

    public function displayTree($path): void
    {
        $tree = Utils::walkTreeFiltered($path);
        Console::writeLine($tree);
    }


}
