<?php

namespace Egg;

define('QUOTE', '"');
define('OPEN_TAG', '<');
define('CLOSE_TAG', '>');
define('TERMINATOR', '/');
define('TAB_MARK', "\t");
define('LF_MARK', "\n");
define('CR_MARK', "\r");
define('SKIP_MARK', '!');
define('QUEST_MARK', '?');
define('STR_EMPTY', '');
define('STR_SPACE', ' ');
define('TAG_PATTERN_ANY', "phx:");

class EggDocument
{

    private int $count = 0;
    private int $cursor = 0;
    private array $matches = [];
    private string $text = STR_EMPTY;
    private int $id = -1;
    private $match = null;
    private array $list = [];
    private array $depths = [];
    private array $matchesByDepth = [];
    private int $endPos = -1;
    private array $allTags = [];

    public function __construct($text)
    {
        $this->text = $text; // . OPEN_TAG . 'Eof' . STR_SPACE . TERMINATOR . CLOSE_TAG;

        $this->endPos = strlen($this->text);
    }

    public function getList(): array
    {
        return $this->list;
    }

    public function isClosedTag($match): bool
    {
        $result = false;

        $tag = isset($match[0]) ? $match[0][0] : '';
        if (empty($tag)) {
            return false;
        }

        $result = substr($tag, -2) === TERMINATOR . CLOSE_TAG;

        return $result;
    }

    public function matchAll(string $tag = TAG_PATTERN_ANY): bool
    {
        $i = 0;
        $s = STR_EMPTY;
        $firstName = STR_EMPTY;
        $secondName = STR_EMPTY;
        $cursor = 0;
        $text = $this->text;
        $parentId = [];
        $depth = 0;
        $parentId[$depth] = -1;
        $allTags = [];

        $text = $this->text;

        $re = '/<\/?([A-Z]\w+)(\s|.*?)+?>/m';


        preg_match_all($re, $text, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER, 0);




        $allTags = $matches;
        $this->allTags = $matches;
        $l = count($allTags);
        $i = 0;
        // list($openElementPos, $closeElementPos, $properties) = $this->_nextTag($matches, $text, $cursor);
        while (count($allTags)) {

            $this->log($allTags, $i);
   
            if ($i === $l) {
                $i = 0;
                $allTags = array_values($allTags);
                $l = count($allTags);
            }

            $match = $allTags[$i];

            // $match = array_shift($allTags);

            if ($this->isClosedTag($match)) {
                $item  = $this->makeClosedTag($match, $depth);
                $this->list[] = $item;
                unset($allTags[$i]);

                $i++;

                continue;
            }

            if ($this->isCloseTag($match) ) {
                $depth--;
            }

            if ($i + 1 < $l) {
                $nextMatch = $allTags[$i + 1];

                if (!$this->isCloseTag($match) && $this->isCloseTag($nextMatch)) {
                    $item = $this->makeOpenTag($match, $depth);
                    $item  = $this->makeCloseTag($item, $nextMatch, $depth);
                    $this->list[] = $item;

                    unset($allTags[$i]);
                    unset($allTags[$i + 1]);

                    $i += 2;

                    continue;
                }

                if (!$this->isCloseTag($match) && !$this->isCloseTag($nextMatch)) {
                    $depth++;
                }

            
            }

            $i++;

        }

        return ($this->count > 0);
    }

    public function log($allTags, $i): void
    {
        $tags = [];
        foreach ($allTags as $key => $match) {
            $tags[$key] = $match[0][0];
        }

        echo $i . PHP_EOL;
        print_r($tags);
    }

    public function isCloseTag($match): bool
    {
        $result = false;

        $tag = $match[0][0];

        $result = substr($tag, 0, 2) === OPEN_TAG . TERMINATOR;

        return $result;
    }

    public function makeOpenTag($match, $depth): array
    {
        $tag = $match[0][0];
        $name =  $match[1][0];

        $i = count($this->list);
        $item = [];

        $item['id'] = $i;
        $item['name'] =  !isset($name) ? 'Fragment' : $name;
        $item['class'] = ''; // ComponentRegistry::read($item['name']);
        $item['method'] = 'echo';
        // $item['component'] = $this->component->getFullyQualifiedFunction();
        $item['text'] = $tag;
        $item['startsAt'] = $match[0][1];
        $item['endsAt'] = $match[0][1] + strlen($item['text']) - 1;
        $item['props'] = ($item['name'] === 'Fragment') ? [] : $this->doArguments($tag);
        $item['depth'] = $depth;
        $item['hasCloser'] = false;
        $item['isCloser'] = false;
        $item['childName'] = '';
        if (!isset($parentId[$depth])) {
            $parentId[$depth] = $i - 1;
        }
        $item['parentId'] = $parentId[$depth];
        // $item['isSibling'] = $isSibling;
        $item['isRegistered'] = false;

        return $item;
    }

    public function makeCloseTag($opener, $closer, $depth): array
    {
        $tag = $closer[0][0];
        $name =  $closer[1][0];

        $i = count($this->list);
        $item = [];

        $item['name'] =  !isset($name) ? 'Fragment' : $name;
        $item['class'] = ''; // ComponentRegistry::read($item['name']);
        $item['method'] = 'echo';
        // $item['component'] = $this->component->getFullyQualifiedFunction();
        $item['text'] = $tag;
        $item['startsAt'] = $closer[0][1];
        $item['endsAt'] = $closer[0][1] + strlen($item['text']) - 1;
        $item['hasCloser'] = false;
        $item['isCloser'] = true;
        $item['childName'] = '';
        $item['parentId'] = $opener['id'];
        // $item['isSibling'] = $isSibling;
        $item['isRegistered'] = false;
        $item['content'] = [];
        $item['content']['startsAt'] = $opener['endsAt'] + 1; // uniqid();
        $item['content']['endsAt'] = $item['startsAt'] - 1; // uniqid();
        $contents = substr($this->text,  $item['content']['startsAt'],  $item['content']['endsAt'] - $item['content']['startsAt'] + 1);
        $item['content']['text'] = '!#base64#' . htmlentities(html_entity_decode($contents)); // uniqid();

        $opener['closer'] = $item;

        return $opener;
    }

    public function makeClosedTag($match, $depth): array
    {
        $tag = $match[0][0];
        $name =  $match[1][0];

        $i = count($this->list);
        $item = [];

        $item['id'] = $i;
        $item['name'] =  !isset($name) ? 'Fragment' : $name;
        $item['class'] = ''; // ComponentRegistry::read($item['name']);
        $item['method'] = 'echo';
        // $item['component'] = $this->component->getFullyQualifiedFunction();
        $item['text'] = $tag;
        $item['startsAt'] = $match[0][1];
        $item['endsAt'] = $match[0][1] + strlen($item['text']) - 1;
        $item['props'] = ($item['name'] === 'Fragment') ? [] : $this->doArguments($tag);
        $item['depth'] = $depth;
        $item['hasCloser'] = false;
        $item['isCloser'] = false;
        $item['childName'] = '';
        if (!isset($parentId[$depth])) {
            $parentId[$depth] = $i - 1;
        }
        $item['parentId'] = $parentId[$depth];
        // $item['isSibling'] = $isSibling;
        $item['isRegistered'] = false;

        return $item;
    }

    public function doArguments(string $componentArgs): ?array
    {
        $result = [];

        $re = '/([A-Za-z0-9_]*)(\[\])?=(\"([\S ][^"]*)\"|\'([\S]*)\'|\{\{ ([\w]*) \}\}|\{([\S ]*)\})/m';

        preg_match_all($re, $componentArgs, $matches, PREG_SET_ORDER, 0);

        foreach ($matches as $match) {
            $key = $match[1];
            $value = substr(substr($match[3], 1), 0, -1);

            if (isset($match[2]) && $match[2] === '[]') {
                if (!isset($result[$key])) {
                    $result[$key] = [];
                }
                $result[$key][] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
