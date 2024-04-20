<?php

namespace Bolero\Forms\Components\Generators\TokenParsers;

use Bolero\Forms\Components\Component;
use Bolero\Forms\Components\Generators\ComponentDocument;
use Bolero\Forms\IO\Utils;
use Bolero\Forms\Registry\ComponentRegistry;

class ChildSlotsParser extends AbstractTokenParser
{
    public function do(null|string|array|object $parameter = null): void
    {
        ComponentRegistry::uncache();

        $motherUID = $this->component->getMotherUID();
        $doc = new ComponentDocument($this->component);
        $doc->matchAll();

        $firstMatch = $doc->getNextMatch();
        if ($firstMatch === null || !$firstMatch->hasCloser()) {
            $this->result = null;
            return;
        }

        $functionName = $firstMatch->getName();

        $parentComponent = new Component($functionName, $motherUID);
        if (!$parentComponent->load()) {
            $this->result = null;
            return;
        }

        $parentFilename = $parentComponent->getFlattenSourceFilename();
        $parentDoc = new ComponentDocument($parentComponent);
        $parentDoc->matchAll();

        $parentHtml = $parentDoc->replaceMatches($doc, $this->html);

        if ($parentHtml !== '') {
            Utils::safeWrite(CACHE_DIR . $motherUID . DIRECTORY_SEPARATOR . $parentFilename, $parentHtml);
            Utils::safeWrite(CACHE_DIR . $motherUID . DIRECTORY_SEPARATOR . $this->component->getFlattenFilename(), $this->html);
        }

        if ($doc->getCount() > 0) {
            ComponentRegistry::cache();
        }

        $this->result = $parentFilename;
    }
}
