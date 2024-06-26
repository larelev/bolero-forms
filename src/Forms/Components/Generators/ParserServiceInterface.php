<?php

namespace Bolero\Forms\Components\Generators;

interface ParserServiceInterface
{
    public function getHtml(): string;

    public function getResult(): null|string|array|bool;

    public function getFuncVariables(): ?array;

    public function getUseVariables(): ?array;

    public function getUses(): ?array;
}
