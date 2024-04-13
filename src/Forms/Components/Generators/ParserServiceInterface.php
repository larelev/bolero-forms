<?php

namespace Bolero\Forms\Components\Generators;

interface ParserServiceInterface {
    public function getHtml(): string;
    public function getResult(): null|string|array|bool;
    public function getVariables(): ?array;
    public function getUses(): ?array;
}
