<?php

namespace AgilePixels\ResourceAbilities;

trait AddsAbilities
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
}
