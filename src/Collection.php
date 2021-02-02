<?php


namespace AgilePixels\ResourceAbilities;

use Illuminate\Database\Eloquent\Collection as BaseCollection;

class Collection extends BaseCollection
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
