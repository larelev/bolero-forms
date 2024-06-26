<?php

namespace Bolero\Forms\Components;

use Bolero\Forms\CLI\Console;
use Bolero\Forms\Components\Generators\ComponentParser;
use Bolero\Forms\Components\Generators\ParserService;
use Bolero\Forms\ElementUtils;
use Bolero\Forms\IO\Utils;
use Bolero\Forms\Registry\CacheRegistry;
use Bolero\Forms\Registry\CodeRegistry;
use Bolero\Forms\Registry\ComponentRegistry;
use Bolero\Forms\Registry\PluginRegistry;
use Bolero\Forms\Registry\WebComponentRegistry;
use Bolero\Forms\Web\Request;

define('INCLUDE_PLACEHOLDER', "include_once CACHE_DIR . '%s';");
define('USE_PLACEHOLDER', "use %s;" . PHP_EOL);

abstract class AbstractFileComponent extends AbstractComponent implements FileComponentInterface
{

    protected ?string $filename = '';

    public function __construct(?string $id = null, string $motherUID = '')
    {
        $this->id = $id ?: '';
        if ($id === null) {
            $this->getUID();
            $this->motherUID = $motherUID ?: $this->uid;

            return;
        }

        ComponentRegistry::uncache();
        $this->class = ComponentRegistry::read($id);
        if ($this->class !== null) {
            $this->filename = ComponentRegistry::read($this->class);
            $this->filename = $this->filename ?: '';

            $this->uid = ComponentRegistry::read($this->filename);
            $this->uid = $this->uid ?: '';
        } else {
            $this->class = WebComponentRegistry::read($id);
            if ($this->class !== null) {
                $this->filename = WebComponentRegistry::read($this->class);
                $this->filename = $this->filename ?: '';

                $this->uid = WebComponentRegistry::read($this->filename);
                $this->uid = $this->uid ?: '';
            }
        }

        if ($this->uid !== $this->id) {
            $this->function = $id;
        }
        if ($this->uid === $this->id) {
            $this->function = self::functionName($this->class);
        }

        $this->motherUID = $motherUID ?: $this->uid;

    }

    public static function createByHtml(string $html) {
        $new = new static();
        $new->code = $html;

        return $new;
    }

    public function analyse(): void
    {
        $parser = new ParserService;
        $parser->doUses($this);
        $parser->doUsesAs($this);
    }

    public function render(?array $functionArgs = null, ?Request $request = null): void
    {
        [$fqFunctionName, $cacheFilename] = $this->renderComponent($this->motherUID, $this->function, $functionArgs);

        echo $this->renderHTML($cacheFilename, $fqFunctionName, $functionArgs, $request);
    }

    public function renderComponent(string $motherUID, string $functionName, ?array $functionArgs = null): array
    {
        [$fqFunctionName, $cacheFilename, $isCached] = $this->findComponent($functionName, $motherUID);
        if (!$isCached) {
            ComponentRegistry::uncache();
            WebComponentRegistry::uncache();

            $fqName = ComponentRegistry::read($functionName);
            $component = ComponentFactory::create($fqName, $motherUID);
            $component->parse();

            $motherUID = $component->getMotherUID();

            $cacheFilename = $motherUID . DIRECTORY_SEPARATOR . $component->getFlattenFilename();
        }

        return [$fqFunctionName, $cacheFilename];
    }

    public function parse(): void
    {
        CodeRegistry::setCacheDirectory(CACHE_DIR . $this->getMotherUID());
        CodeRegistry::uncache();
        WebComponentRegistry::uncache();

        $parser = new ParserService();

        $parser->doUses($this);
        $parser->doUsesAs($this);

        $parser->doHeredoc($this);
        $this->code = $parser->getHtml();

        $parser->doInlineCode($this);
        $this->code = $parser->getHtml();

        $parser->doChildrenDeclaration($this);
        $this->children = $parser->getChildren();

        $parser->doArrays($this);
        $this->code = $parser->getHtml();

        $parser->doUseEffect($this);
        $this->code = $parser->getHtml();

        $parser->doWebComponent($this);

        $parser->doUseVariables($this);
        $this->code = $parser->getHtml();

        $parser->doNamespace($this);
        $this->code = $parser->getHtml();

        $parser->doFragments($this);
        $this->code = $parser->getHtml();
        $filename = $this->getFlattenSourceFilename();
        Utils::safeWrite(CACHE_DIR . $this->getMotherUID() . DIRECTORY_SEPARATOR . $filename, $this->code);
        $this->updateComponent($this);

        $parser->doChildSlots($this);
        $this->code = $parser->getHtml();
        $this->updateComponent($this);

        while ($compz = $this->getDeclaration()->getComposition() !== null) {
            $parser->doOpenComponents($this);
            $this->code = $parser->getHtml();
            $this->updateComponent($this);

            $parser->doClosedComponents($this);
            $this->code = $parser->getHtml();
            $this->updateComponent($this);

            $parser->doIncludes($this);
            $this->code = $parser->getHtml();
        }

        CodeRegistry::cache();
    }

    public function getFlattenSourceFilename(): string
    {
        return static::getFlatFilename($this->filename);
    }

    public static function getFlatFilename(string $basename): string
    {
        $basename = pathinfo($basename, PATHINFO_BASENAME);

        return str_replace('/', '_', $basename);
    }

    public static function updateComponent(FileComponentInterface $component): string
    {
        $uid = $component->getUID();
        $motherUID = $component->getMotherUID();
        $filename = $component->getFlattenSourceFilename();

        $comp = new Component($uid, $motherUID);
        $comp->load($filename);
        $parser = new ComponentParser($comp);
        $struct = $parser->doDeclaration($uid);
        $decl = $struct->toArray();

        CodeRegistry::write($comp->getFullyQualifiedFunction(), $decl);
        CodeRegistry::cache();

        return $filename;
    }

    public function load(?string $filename = null): bool
    {
        $filename = $filename ?: '';

        $this->filename = ($filename !== '') ? $filename : $this->filename;

        if ($this->filename === '') {
            return false;
        }

        $this->code = Utils::safeRead(CACHE_DIR . $this->motherUID . DIRECTORY_SEPARATOR . $this->filename);
        if ($this->code === null) {
            $this->code = Utils::safeRead(COPY_DIR . $this->filename);
        }

        [$this->namespace, $this->function, $this->bodyStartsAt] = ElementUtils::getFunctionDefinition($this->code);
        if (!$this->bodyStartsAt) {
            $this->makeComponent($this->filename, $this->code);
            [$this->namespace, $this->function, $this->bodyStartsAt] = ElementUtils::getFunctionDefinition($this->code);
        }
        return $this->code !== null;
    }

    public abstract function makeComponent(string $filename, string &$html): void;

    public function getFlattenFilename(): string
    {
        return static::getFlatFilename($this->filename);
    }

    public function identifyComponents(array &$list, ?string $motherUID = null, ?FileComponentInterface $component = null): void
    {

        $isRoute = false;
        if ($component === null) {
            $isRoute = true;
            $component = $this;

            $motherUID = $component->getMotherUID();

            if (!file_exists(UNIQUE_DIR . $motherUID)) {
                mkdir(UNIQUE_DIR . $motherUID, 0775);

                $flatFilename = CodeRegistry::getFlatFilename() . '.json';
                copy(CACHE_DIR . $flatFilename, UNIQUE_DIR . $motherUID . DIRECTORY_SEPARATOR . $flatFilename);
            }
        }

        $uid = $component->getUID();

        $cachedir = UNIQUE_DIR . $motherUID . DIRECTORY_SEPARATOR;
        $copyFile = $component->getFlattenSourceFilename();
        $funcName = $component->getFunction();
        $fqFuncName = $component->getFullyQualifiedFunction();
        $parentHtml = $component->getCode();
        $token = '_' . str_replace('-', '', $uid);

        $cacheFile = $isRoute ? $cachedir . $funcName . $token . PREHTML_EXTENSION : $cachedir . $copyFile;

        if (file_exists($cacheFile)) {
            $parentHtml = file_get_contents($cacheFile);
        }

        $componentList = $component->composedOf();

        if ($componentList === null) {
            copy(COPY_DIR . $copyFile, $cacheFile);
        }

        if (!$isRoute) {
            $copyFile = str_replace(PREHTML_EXTENSION, $token . PREHTML_EXTENSION, $copyFile);


            $re = '/(function )(' . $funcName . ')([ ]*\(.*\))/m';
            $subst = '$1$2' . $token . '$3';

            $parentHtml = preg_replace($re, $subst, $parentHtml);
        }

        foreach ($componentList as $entity) {
            $funcName = $entity->getName();

            $fqFuncName = ComponentRegistry::read($funcName);

            if ($fqFuncName === null) {
                continue;
            }
            $nextComponent = $list[$fqFuncName];

            $uid = $entity->getUID();
            $nextComponent->uid = $uid;

            $token = '_' . str_replace('-', '', $uid);

            $isPlugin = null !== $nextCopyFile = PluginRegistry::read($fqFuncName);

            if (!$isPlugin) {
                $nextCopyFile = $nextComponent->getSourceFilename();
                $nextCopyFile = str_replace(PREHTML_EXTENSION, $token . PREHTML_EXTENSION, $nextCopyFile);
            }

            $re = '/(<)(' . $funcName . ')(((?!_[A-F0-9]{32}).)*)(>)/';
            $subst = '$1$2' . $token . '$3$5';

            $parentHtml = preg_replace($re, $subst, $parentHtml, 1);

            $re = '/(.*)(<\/)(' . $funcName . ')(>)/su';
            $subst = '$1$2$3' . $token . '$4';

            $parentHtml = preg_replace($re, $subst, $parentHtml, 1);

            if ($nextComponent !== null) {
                $component->identifyComponents($list, $motherUID, $nextComponent);
            }
        }

        if ($isPlugin) {
            copy(COPY_DIR . $copyFile, $cachedir . $copyFile);
        } else {
            Utils::safeWrite($cachedir . $copyFile, $parentHtml);
        }
    }

    public function getSourceFilename(): string
    {
        return $this->filename;
    }

    public function copyComponents(array &$list, ?string $motherUID = null, ?ComponentInterface $component = null): ?string
    {
        if ($component === null) {
            $component = $this;
            $motherUID = $component->getUID();
            if (!file_exists(CACHE_DIR . $motherUID)) {
                mkdir(CACHE_DIR . $motherUID, 0775);

                $flatFilename = CodeRegistry::getFlatFilename() . '.json';
                copy(CACHE_DIR . $flatFilename, CACHE_DIR . $motherUID . DIRECTORY_SEPARATOR . $flatFilename);
            }
        }

        $cachedir = CACHE_DIR . $motherUID . DIRECTORY_SEPARATOR;
        $componentList = $component->composedOf();
        $copyFile = $component->getFlattenSourceFilename();

        if ($componentList === null) {
            if (!file_exists($cachedir . $copyFile)) {
                copy(COPY_DIR . $copyFile, $cachedir . $copyFile);
            }

            return $copyFile;
        }

        $fqFuncName = $component->getFullyQualifiedFunction();
        foreach ($componentList as $entity) {

            $funcName = $entity->getName();
            $fqFuncName = ComponentRegistry::read($funcName);

            if ($fqFuncName === null) {
                continue;
            }
            $nextComponent = !isset($list[$fqFuncName]) ? null : $list[$fqFuncName];

            $nextCopyFile = '';
            if ($nextComponent !== null) {
                $nextCopyFile = $nextComponent->getFlattenSourceFilename();
            }

            if ($nextComponent === null) {
                $nextCopyFile = PluginRegistry::read($fqFuncName);
            }
            if (file_exists($cachedir . $nextCopyFile)) {
                continue;
            }

            if ($nextComponent === null) {
                continue;
            }
            $component->copyComponents($list, $motherUID, $nextComponent);
        }

        if (!file_exists($cachedir . $copyFile)) {
            copy(COPY_DIR . $copyFile, $cachedir . $copyFile);
        }

        return $copyFile;
    }

    public function updateFile(): void
    {
        $cp = new ComponentParser($this);
        $struct = $cp->doDeclaration();
        $decl = $struct->toArray();
        $filename = $this->getFlattenSourceFilename();
        Utils::safeWrite(CACHE_DIR . $this->motherUID . DIRECTORY_SEPARATOR . $filename, $this->code);

        CodeRegistry::write($this->getFullyQualifiedFunction(), $decl);
        CodeRegistry::cache();
    }

    protected function cacheHtml(): ?string
    {
        return  $this->cacheFile(CACHE_DIR);
    }

    protected function cacheJavascript(): ?string
    {
        return  $this->cacheFile(RUNTIME_JS_DIR);
    }

    private function cacheFile($cacheDir): ?string
    {
        $cache_file = static::getFlatFilename($this->filename);
        $result = Utils::safeWrite($cacheDir . $this->motherUID . DIRECTORY_SEPARATOR . $cache_file, $this->code);

        $cache = (($cache = CacheRegistry::read($this->motherUID)) === null) ? [] : $cache;

        $cache[$this->getFullyQualifiedFunction()] = static::getFlatFilename($this->getSourceFilename());
        CacheRegistry::write($this->motherUID, $cache);
        CacheRegistry::cache();

        return $result === null ? $result : $cache_file;
    }
}
