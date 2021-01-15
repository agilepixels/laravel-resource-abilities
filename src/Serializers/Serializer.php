<?php

namespace AgilePixels\ResourceAbilities\Serializers;

use AgilePixels\ResourceAbilities\AbilityContainer;

interface Serializer
{
    public function format(AbilityContainer $abilityContainer): array;
}
