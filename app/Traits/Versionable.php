<?php

namespace App\Traits;

use App\Models\Version;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Versionable
{
    public static function bootVersionable()
    {
        static::created(function (Model $model) {
            $model->storeVersion($model);
        });

        static::updated(function (Model $model) {
            if ($model->wasChanged()) {
                $model->storeVersion($model);
            }
        });

        static::deleting(function (Model $model) {
            $model->versions()->delete();
        });
    }

    public function versionableFields(): array
    {
        return $this->fillable;
    }

    public function storeVersion(Model $model): void
    {
        Version::create([
            'versionable_id' => $this->id,
            'versionable_type' => get_class($this),
            'version' => $this->nextVersionNumber(),
            'data' => $this->prepareVersionData(),
        ]);

        $model->setRawAttributes($model->fresh()->getAttributes());
    }

    protected function prepareVersionData(): array
    {
        return $this->only($this->versionableFields());
    }

    public function nextVersionNumber(): int
    {
        return ($this->versions()->max('version') ?? 0) + 1;
    }

    public function versions(): MorphMany
    {
        return $this->morphMany(Version::class, 'versionable');
    }

    public function getCurrentVersion(): int
    {
        return $this->versions()->max('version') ?? 0;
    }

    public function getOperationStatus(): string
    {
        return match (true) {
            $this->wasRecentlyCreated => 'created',
            $this->wasChanged() => 'updated',
            default => 'duplicate',
        };
    }
}
