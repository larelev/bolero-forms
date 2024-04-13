<?php

namespace Bolero\Forms\Components\Generators;

use Bolero\Forms\Components\FileComponentInterface;
use Bolero\Forms\Components\Generators\TokenParsers\ArgumentsParser;
use Bolero\Forms\Components\Generators\TokenParsers\ArraysParser;
use Bolero\Forms\Components\Generators\TokenParsers\ChildrenDeclarationParser;
use Bolero\Forms\Components\Generators\TokenParsers\ChildSlotsParser;
use Bolero\Forms\Components\Generators\TokenParsers\ClosedComponentsParser;
use Bolero\Forms\Components\Generators\TokenParsers\EchoParser;
use Bolero\Forms\Components\Generators\TokenParsers\EmptyComponentsParser;
use Bolero\Forms\Components\Generators\TokenParsers\FragmentsParser;
use Bolero\Forms\Components\Generators\TokenParsers\HeredocParser;
use Bolero\Forms\Components\Generators\TokenParsers\NamespaceParser;
use Bolero\Forms\Components\Generators\TokenParsers\OpenComponentsParser;
use Bolero\Forms\Components\Generators\TokenParsers\PhpTagsParser;
use Bolero\Forms\Components\Generators\TokenParsers\UseEffectParser;
use Bolero\Forms\Components\Generators\TokenParsers\UsesAsParser;
use Bolero\Forms\Components\Generators\TokenParsers\UsesParser;
use Bolero\Forms\Components\Generators\TokenParsers\UseVariablesParser;
use Bolero\Forms\Components\Generators\TokenParsers\ValuesParser;
use Bolero\Forms\Components\Generators\TokenParsers\WebComponentParser;
use Bolero\Forms\Registry\ComponentRegistry;

class ParserService implements ParserServiceInterface
{
    protected ?object $component = null;
    protected array $useVariables = [];
    protected array $useTypes = [];
    protected string $html = '';
    protected ?object $children = null;
    protected $componentList = [];
    protected $openComponentList = [];
    protected $result = null;

    public function getVariables(): ?array
    {
        return $this->useVariables;
    }

    public function getUses(): ?array
    {
        return $this->useTypes;
    }

    public function getResult(): null|string|array|bool
    {
        return $this->result;
    }

    public function getHtml(): string
    {
        return $this->html;
    }

    public function getChildren(): ?object
    {
        return $this->children;
    }

    public function doArguments(FileComponentInterface $component): void
    {
        $p = new ArgumentsParser($component);
        $p->do();

        $this->result = $p->getResult();
    }

    public function doChildSlots(FileComponentInterface $component): void
    {
        $p = new ChildSlotsParser($component);
        $p->do();
        $this->html = $p->getHtml();
        $this->result = $p->getResult();
    }

    public function doUses(FileComponentInterface $component): void
    {
        $p = new UsesParser($component);
        $p->do();
        $this->useTypes = array_merge($this->useTypes, $p->getUses());
    }

    public function doUsesAs(FileComponentInterface $component): void
    {
        $p = new UsesAsParser($component);
        $p->do();
        $this->useTypes = array_merge($this->useTypes, $p->getUses());
    }

    public function doHeredoc(FileComponentInterface $component): void
    {
        $p = new HeredocParser($component);
        $p->do();
        $this->html = $p->getHtml();
    }

    public function doPhpTags(FileComponentInterface $component): void
    {
        $p = new PhpTagsParser($component);
        $p->do();
        $this->html = $p->getHtml();
    }

    public function doChildrenDeclaration(FileComponentInterface $component): void
    {
        $p = new ChildrenDeclarationParser($component);
        $p->do();
        $this->children = (object) $p->getResult();
    }

    public function doValues(FileComponentInterface $component): void
    {
        $p = new ValuesParser($component);
        $p->do($this->useVariables);
        $this->useVariables = $p->getVariables();
        $this->html = $p->getHtml();
    }

    public function doEchoes(FileComponentInterface $component): void
    {
        $p = new EchoParser($component);
        $p->do($this->useVariables);
        $this->useVariables = $p->getVariables();
        $this->html = $p->getHtml();
    }

    public function doArrays(FileComponentInterface $component): void
    {
        $p = new ArraysParser($component);
        $p->do($this->useVariables);
        $this->useVariables = $p->getVariables();
        $this->html = $p->getHtml();
    }

    public function doUseEffect(FileComponentInterface $component): void
    {
        $p = new UseEffectParser($component);
        $p->do();
        $this->html = $p->getHtml();
    }

    public function doUseSlot(FileComponentInterface $component): void
    {
        $p = new UseSlotParser($component);
        $p->do();
        $this->html = $p->getHtml();
    }

    public function doUseVariables(FileComponentInterface $component): void
    {
        $p = new UseVariablesParser($component);
        $p->do($this->useVariables);
        $this->useVariables = $p->getVariables();
        $this->html = $p->getHtml();
    }

    public function doWebComponent(FileComponentInterface $component): void
    {
        $p = new WebComponentParser($component);
        $p->do($this->useVariables);
        $this->useVariables = $p->getVariables();
    }

    public function doNamespace(FileComponentInterface $component): void
    {
        $p = new NamespaceParser($component);
        $p->do();
        $this->html = $p->getHtml();
    }

    public function doFragments(FileComponentInterface $component): void
    {
        $p = new FragmentsParser($component);
        $p->do();
        $this->html = $p->getHtml();
    }

    public function doClosedComponents(FileComponentInterface $component): void
    {
        $p = new ClosedComponentsParser($component);
        $p->do();
        $this->componentList = $p->getResult();
        $this->html = $p->getHtml();
    }

    public function doOpenComponents(FileComponentInterface $component): void
    {
        $p = new OpenComponentsParser($component);
        $p->do($this->useVariables);
        $this->openComponentList = $p->getResult();
        $this->html = $p->getHtml();
    }

    public function doEmptyComponents(FileComponentInterface $component): void
    {
        $p = new EmptyComponentsParser($component);
        $p->do();
        $this->html = $p->getHtml();
        $this->result = $p->getResult();
    }

    public function doIncludes(FileComponentInterface $component): void
    {
        $componentList = array_unique(array_merge($this->componentList, $this->openComponentList));

        ComponentRegistry::uncache();
        $motherUID = $component->getMotherUID();

        foreach ($componentList as $componentName) {
            [$fqFunctionName, $cacheFilename] = $component->renderComponent($motherUID, $componentName);

            $include = str_replace('%s', $cacheFilename, INCLUDE_PLACEHOLDER);

            $re = '/(namespace +[\w\\\\]+;)/m';
            preg_match_all($re, $this->html, $matches, PREG_SET_ORDER, 0);

            if (!isset($matches[0])) {
                $re = '/(<\?php)/m';
            }

            $subst = '$1' . PHP_EOL . '<Include />';
            $this->html = preg_replace($re, $subst, $this->html, 1);

            $this->html = str_replace('<Include />', $include, $this->html);
        }
    }
}
