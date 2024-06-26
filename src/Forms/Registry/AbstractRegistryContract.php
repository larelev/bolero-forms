<?php

namespace Bolero\Forms\Registry;

abstract class AbstractRegistryContract
{

    abstract protected function _items(): array;

    abstract protected function _write(string $key, $value): void;

    abstract protected function _read($key, $value = null);

    abstract protected function _delete(string $key): void;

    abstract protected function _exists(string $key): bool;

    abstract protected function _cache(bool $asArray = false): bool;

    abstract protected function _uncache(bool $asArray = false): bool;

    abstract protected function _getFlatFileName(): string;

    abstract protected function _getCacheFileName(bool $asArray = false): string;

    abstract protected function _setCacheDirectory(string $directory): void;
}
