<?php

namespace AgilePixels\ResourceAbilities;

use JetBrains\PhpStorm\Pure;

class AbilityContainer
{
    public function __construct(public string $ability, public bool $granted) {}

    #[Pure]
    public static function make(string $ability, bool $granted): AbilityContainer
    {
        return new self($ability, $granted);
    }
}