<?php

namespace AgilePixels\ResourceAbilities\Models;

trait HasAbilities
{
    /** The abilities that should be loaded on response. */
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
