<?php

namespace Bolero\Forms\Components;

use BadFunctionCallException;
use Bolero\Forms\ElementTrait;
use Bolero\Forms\Registry\CacheRegistry;
use Bolero\Forms\Registry\CodeRegistry;
use Bolero\Forms\Registry\ComponentRegistry;
use Bolero\Forms\Tree\Tree;
use Bolero\Forms\Plugins\Router\RouterService;
use Exception;
use ReflectionFunction;
use stdClass;
use Bolero\Forms\Web\Request;

abstract class AbstractComponent extends Tree implements ComponentInterface
{
    use ElementTrait;

    protected ?string $code;
    protected ?stdClass $children = null;
    protected ?ComponentDeclaration $declaration = null;
    protected ?ComponentEntity $entity = null;
    protected int $bodyStartsAt = 0;

    public function getBodyStart(): int
    {
        return $this->bodyStartsAt;
    }

    /**
     * @throws Exception
     */
    public function getDeclaration(): ?ComponentDeclaration
    {
        if ($this->declaration === null) {
            $this->setDeclaration();
        }

        return $this->declaration;
    }

    protected function setDeclaration(): void
    {
        $fqName = ComponentRegistry::read($this->uid);

        if ($fqName === null) {
            $fqName = $this->getFullyQualifiedFunction();
            if ($fqName === null) {
                throw new Exception('Please the component is defined in the registry before asking for its entity');
            }
        }
        CodeRegistry::setCacheDirectory(CACHE_DIR . $this->getMotherUID());

        $list = CodeRegistry::read($fqName);
        $struct = new ComponentDeclarationStructure($list);
        $decl = new ComponentDeclaration($struct);

        $this->declaration = $decl;
    }

    public function resetDeclaration(): void
    {
        $this->declaration = null;
    }

    public function getEntity(): ?ComponentEntity
    {
        if ($this->entity === null) {
            $this->setEntity();
        }

        return $this->entity;
    }

    /**
     * @throws Exception
     */
    protected function setEntity()
    {
        $decl = $this->getDeclaration();
        $this->entity = $decl->getComposition();
    }

    public function getParentHTML(): ?string
    {
        return $this->parentHTML;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getFullyQualifiedFunction(): ?string
    {
        if ($this->function === null) return null;
        return $this->namespace . '\\' . $this->function;
    }

    public function getFunction(): ?string
    {
        return $this->function;
    }

    public function composedOfUnique(): ?array
    {
        $result = $this->composedOf();

        if ($result === null) return null;

        return array_unique($result);
    }

    public function composedOf(): ?array
    {
        $names = [];

        $this->forEach(function (ComponentEntityInterface $item, $key) use (&$names) {
            $names[] = $item;
        }, $this);

        $names = array_filter($names, function ($item) {
            return $item !== null;
        });

        if (count($names) === 0) {
            $names = null;
        }

        return $names;
    }

    public function findComponent(string $componentName, string $motherUID): array
    {
        ComponentRegistry::uncache();
        $uses = ComponentRegistry::items();
        $fqFuncName = $uses[$componentName] ?? null;

        if ($fqFuncName === null) {
            throw new BadFunctionCallException('The component ' . $componentName . ' does not exist.');
        }

        CacheRegistry::uncache();

        if ($motherUID === '') {
            $filename = $uses[$fqFuncName];
            $motherUID = $uses[$filename];
        }
        $filename = CacheRegistry::read($motherUID, $fqFuncName);
        $filename = ($filename !== null) ? $motherUID . DIRECTORY_SEPARATOR . $filename : $filename;
        $isCached = $filename !== null;

        return [$fqFuncName, $filename, $isCached];
    }

    /**
     * @throws \ReflectionException
     */
    public function renderHTML(string $cacheFilename, string $fqFunctionName, ?array $functionArgs = null, ?Request $request = null): string
    {
        include_once CACHE_DIR . $cacheFilename;

        $funcReflection = new ReflectionFunction($fqFunctionName);
        $funcParams = $funcReflection->getParameters();

        $bodyProps = null;
        if($request !== null && $request->headers->contains('application/json', 'content-type')) {
            $bodyProps = json_decode($request->body);
        }

        $html = '';

        if ($funcParams === [] && $bodyProps === null) {
            ob_start();
            $fn = call_user_func($fqFunctionName);
            $fn();
            $html = ob_get_clean();
        } else {
            $props = null;
            if ((null !== $args = json_decode(json_encode($functionArgs))) && count($functionArgs) > 0) {
                $props = new stdClass;
                foreach ($args as $field => $value) {
                    $props->{$field} = urldecode($value);
                }
            } else {
                $routeProps = RouterService::findRouteArguments($fqFunctionName);
                if ($routeProps !== null) {
                    $props = new stdClass;
                    foreach ($routeProps as $field => $value) {
                        $props->{$field} = null;
                    }
                }
            }

            if($bodyProps !== null) {
                if($props === null) {
                    $props = new stdClass;
                }
                foreach ($bodyProps as $field => $value) {
                    $props->{$field} = $value;
                }
            }
            ob_start();
            $fn = call_user_func($fqFunctionName, $props);
            $fn();
            $html = ob_get_clean();
        }

        // if ($funcName === 'App') {
        //     $html = self::format($html);
        // }

        return $html;
    }

    protected function format(string $html): string
    {
        $config = [
            'indent' => true,
            'output-html' => true,
            'wrap' => 200
        ];

        $tidy = new \tidy;
        $tidy->parseString($html, $config, 'utf8');
        $tidy->cleanRepair();

        return $tidy->value;
    }
}
