<?php

namespace AgilePixels\ResourceAbilities;

trait HasAbilities
{
    protected array $abilities = [];

    public function getAbilities(): array
    {
        return $this->abilities;
    }

    public function addAbility(string $ability): self
    {
        $this->abilities[] = $ability;

        return $this;
    }

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
}
