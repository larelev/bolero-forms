<?php

namespace Bolero\Forms\Plugins\WebComponent;

use Bolero\Forms\WebComponents\ManifestEntity;

interface WebComponentServiceInterface
{

    public function isPending(): bool;

    public function markAsPending(): void;

    public function getBody(string $tag): ?string;

    public function readManifest(): ManifestEntity;

    public function storeHTML(string $html): void;

}
