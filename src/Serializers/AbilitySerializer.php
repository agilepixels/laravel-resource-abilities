<?php

namespace AgilePixels\ResourceAbilities\Serializers;

use AgilePixels\ResourceAbilities\AbilityContainer;

class AbilitySerializer implements Serializer
{
    public function format(AbilityContainer $abilityContainer): array
    {
        return [
            $abilityContainer->ability => $abilityContainer->granted,
        ];
    }
}
