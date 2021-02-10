<?php

namespace AgilePixels\ResourceAbilities;

/**
 * @method static Builder query()
 */
trait HasAbilities
{
    use AddsAbilities;

    public function newInstance($attributes = [], $exists = false): static
    {
        $model = parent::newInstance($attributes, $exists);

        $model->mergeAbilities($this->abilities);

        return $model;
    }

    public function newCollection(array $models = [])
    {
        return new Collection($models);
    }
}
