<?php

namespace Bolero\Forms\Commands\MakeSkeleton;

use Bolero\Forms\Commands\AbstractCommandLib;
use Bolero\Forms\IO\Utils;

class Lib extends AbstractCommandLib
{

    public function makeSkeleton(): void
    {
        $sample = BOLERO_FORMS_ROOT . 'Samples' . DIRECTORY_SEPARATOR . 'Skeleton';

        Utils::safeMkDir(SRC_ROOT);
        $destDir = realpath(SRC_ROOT);

        if (!file_exists($sample) || !file_exists($destDir)) {
            return;
        }

        $tree = Utils::walkTreeFiltered($sample);

        foreach ($tree as $filePath) {
            Utils::safeWrite($destDir . $filePath, '');
            copy($sample . $filePath, $destDir . $filePath);
        }
    }
}

