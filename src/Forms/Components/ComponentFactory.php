<?php

namespace Bolero\Forms\Components;

use Bolero\Forms\Registry\PluginRegistry;
use Bolero\Forms\Registry\ComponentRegistry;
use Bolero\Forms\Registry\WebComponentRegistry;

class ComponentFactory
{
    public static function create(string $fullyQualifiedName, string $motherUID): AbstractFileComponent
    {

        $filename = ComponentRegistry::read($fullyQualifiedName);
        $isPlugin = $filename === null && ($filename = PluginRegistry::read($fullyQualifiedName)) !== null;

        if ($isPlugin) {
            $uid = PluginRegistry::read($filename);
            $plugin = new Plugin($uid, $motherUID);
            $plugin->load($filename);

            return $plugin;
        }

        $isWebComponent = $filename === null && ($filename = WebComponentRegistry::read($fullyQualifiedName)) !== null;

        if ($isWebComponent) {
            $uid = WebComponentRegistry::read($filename);
            $webComponent = new WebComponent($uid, $motherUID);
            $webComponent->load($filename);

            return $webComponent;
        }

        $uid = ComponentRegistry::read($filename);
        $comp = new Component($uid, $motherUID);
        $comp->load($filename);

        return $comp;
    }
}
