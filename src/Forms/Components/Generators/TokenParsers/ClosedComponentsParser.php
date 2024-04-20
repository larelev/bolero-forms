<?php

namespace Bolero\Forms\Components\Generators\TokenParsers;

use Bolero\Forms\Components\ComponentEntityInterface;
use Bolero\Forms\IO\Utils;
use Bolero\Forms\Plugins\Route\RouteEntity;
use Bolero\Forms\Plugins\Route\RouteStructure;
use Bolero\Forms\Registry\ComponentRegistry;
use Bolero\Forms\Registry\RouteRegistry;
use Bolero\Forms\Registry\WebComponentRegistry;
use Bolero\Forms\WebComponents\ManifestReader;
use Bolero\Framework\Logger\Logger;

final class ClosedComponentsParser extends AbstractTokenParser
{
    public function do(null|string|array|object $parameter = null): void
    {
        $this->result = [];

        $comp = $this->component;
        $decl = $comp->getDeclaration();
        $cmpz = $decl->getComposition();
        $parent = $parameter;
        $muid = $comp->getMotherUID();

        if ($cmpz === null) {
            return;
        }

        $subject = $this->html;

        $closure = function (ComponentEntityInterface $item, int $index) use (&$subject, &$result, $parent, $muid) {

            if ($item->hasCloser()) {
                return;
            }

            $uid = $item->getUID();
            $component = $item->getText();
            $componentName = $item->getName();
            $componentArgs = [];
            $componentArgs['uid'] = $uid;

            $args = '';
            if ($item->props() !== null) {
                $componentArgs = array_merge($componentArgs, $item->props());
                $args = json_encode($componentArgs, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
                $args = "json_decode('$args')";
            }

            $funcName = ComponentRegistry::read($componentName);
            $filename = ComponentRegistry::read($funcName);

            $componentRender = "\t\t\t<?php \$fn = \\{$funcName}($args); \$fn(); ?>\n";

            if ($filename === null) {
                $filename = WebComponentRegistry::read($funcName);
                if ($filename !== null) {

                    $reader = new ManifestReader($this->component->getMotherUID(), $componentName);
                    $manifest = $reader->read();
                    $tag = $manifest->getTag();
                    $text = str_replace($componentName, $tag, $component);
                    $text = str_replace('/>', '>', $text);
                    $text .= '</' . $tag . '>';
                    Utils::safeWrite(CACHE_DIR . $this->component->getMotherUID() . DIRECTORY_SEPARATOR . $componentName . $uid . '.txt', $text);
                }
            }

            $subject = str_replace($component, $componentRender, $subject);

            if($parent !== null && $parent->getName() == 'Route' && $item->getName() != 'Route') {

                $filename = $muid . DIRECTORY_SEPARATOR . ComponentRegistry::read($funcName);

                $route = new RouteEntity( new RouteStructure($parent->props()) );
                $middlewareHtml = "function() {\n\tinclude_once CACHE_DIR . '$filename';\n\t\$fn = \\{$funcName}($args); \$fn();\n}\n";
                RouteRegistry::uncache();
                $methodRegistry = RouteRegistry::read($route->getMethod()) ?: [];

                $methodRegistry[$route->getRule()] = [
                    'rule' => $route->getRule(),
                    'redirect' => $route->getRedirect(),
                    'error' => $route->getError(),
                    'exact' => $route->isExact(),
                    'middlewares' => [$middlewareHtml,],
                    'translate' => $route->getRule(),
                    'normal' => $route->getRule(),
                ];
                RouteRegistry::write($route->getMethod(), $methodRegistry);

                RouteRegistry::cache();
            }

            $this->result[] = $componentName;

            $filename = $this->component->getFlattenSourceFilename();
            Utils::safeWrite(CACHE_DIR . $this->component->getMotherUID() . DIRECTORY_SEPARATOR . $filename, $subject);
        };

        if (!$cmpz->hasChildren()) {
            $closure($cmpz, 0);
        }
        if ($cmpz->hasChildren()) {
            $cmpz->forEach($closure, $cmpz);
        }


        $this->html = $subject;
    }
}
