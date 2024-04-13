<?php

namespace Bolero\Forms\Components\Generators;

use Bolero\Forms\Components\ComponentEntity;
use Bolero\Forms\Components\ComponentStructure;

class RawHtmlParser implements RawHtmlParserInterface
{
    private array $list;

    public function __construct(private string $html)
    {
    }

    public function doTag(string $tag): void
    {
        $parser = new ComponentParser($this->html);
        $parser->doComponents($tag);
        $this->list = $parser->getList();
        unset($parser);
    }

    public function getInnerHtml(): array
    {
        $result = [];
        if (count($this->list) === 0) {
            return $result;
        }
        foreach ($this->list as $current) {
            $struct = new ComponentStructure($current);
            $entity = new ComponentEntity($struct);
            $result[] = $entity->getInnerHTML();
        }

        return $result;
    }

    public function getOuterHtml(): array
    {
        $result = [];
        if (count($this->list) === 0) {
            return $result;
        }
        foreach ($this->list as $current) {
            $struct = new ComponentStructure($current);
            $entity = new ComponentEntity($struct);
            $html = $current['text'] . PHP_EOL;
            $html .= $entity->getInnerHTML() . PHP_EOL;
            $html .= $current['closer']['text'] . PHP_EOL;

            $result[] = $html;
        }

        return $result;
    }
}
