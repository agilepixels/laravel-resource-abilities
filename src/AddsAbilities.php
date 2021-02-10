<?php

namespace AgilePixels\ResourceAbilities;

trait AddsAbilities
{
    protected array $abilities = [];

    public function getAbilities(): array
    {
        return $this->abilities;
    }

    public function addAbility(string|array $ability): self
    {
        $this->mergeAbilities(is_string($ability) ? func_get_args() : $ability);

        return $this;
    }

    public function mergeAbilities(array $abilities): static
    {
        $this->abilities = array_merge($this->abilities, $abilities);

        return $this;
    }
}
