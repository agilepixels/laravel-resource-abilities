<?php

namespace AgilePixels\ResourceAbilities;

trait AddsAbilities
{
    protected bool $withAllAbilities = true;

    protected array $abilities = [];

    public function getWithAllAbilities(): bool
    {
        return $this->withAllAbilities;
    }

    public function getAbilities(): array
    {
        return $this->abilities;
    }

    public function addAbility(string | array $ability): static
    {
        $this->mergeAbilities(
            is_string($ability) ? func_get_args() : $ability,
            false
        );

        return $this;
    }

    public function mergeAbilities(array $abilities, bool $withAllAbilities): static
    {
        $this->abilities = array_merge($this->abilities, $abilities);
        $this->withAllAbilities = $withAllAbilities;

        return $this;
    }

    public function withAllAbilities(bool $withAllAbilities = true): static
    {
        $this->withAllAbilities = $withAllAbilities;

        return $this;
    }
}
