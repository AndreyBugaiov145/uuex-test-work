<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface VersionableInterface
{
    public function versionableFields(): array;

    public function storeVersion(Model $model): void;

    public function nextVersionNumber(): int;

    public function versions(): MorphMany;

    public function getCurrentVersion(): int;

    public function getOperationStatus(): string;
}
