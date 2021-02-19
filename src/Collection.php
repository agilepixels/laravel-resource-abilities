<?php


namespace AgilePixels\ResourceAbilities;

use Illuminate\Database\Eloquent\Collection as BaseCollection;

class Collection extends BaseCollection
{
    use AddsAbilities;

    public function toBase()
    {
        return new self($this);
    }
}
