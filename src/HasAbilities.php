<?php

namespace AgilePixels\ResourceAbilities;

trait HasAbilities
{
    use AddsAbilities;

    public function mergeAbilities(array $abilities): static
    {
        $this->abilities = array_merge($this->abilities, $abilities);

        return $this;
    }

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
