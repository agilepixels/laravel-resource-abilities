<?php

namespace AgilePixels\ResourceAbilities\Serializers;

use AgilePixels\ResourceAbilities\AbilityContainer;

class ExtendedAbilitySerializer implements Serializer
{
    public function format(AbilityContainer $abilityContainer): array
    {
        return [
            $abilityContainer->ability => [
                'ability' => $abilityContainer->ability,
                'granted' => $abilityContainer->granted,
            ],
        ];
    }
}
