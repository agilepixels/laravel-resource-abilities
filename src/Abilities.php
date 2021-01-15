<?php

namespace AgilePixels\ResourceAbilities;

use AgilePixels\ResourceAbilities\AbilityTypes\GateAbilityType;
use AgilePixels\ResourceAbilities\AbilityTypes\PolicyAbilityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Abilities
{
    protected Collection $abilityTypes;

    public function __construct()
    {
        $this->abilityTypes = new Collection();
    }

    public function gate(string $ability, Model $model): GateAbilityType
    {
        $gateAbilityType = GateAbilityType::make($ability, $model);

        $this->abilityTypes[] = $gateAbilityType;

        return $gateAbilityType;
    }

    public function policy(string $policy, Model $model): PolicyAbilityType
    {
        $policyAbilityType = PolicyAbilityType::make($policy, $model);

        $this->abilityTypes[] = $policyAbilityType;

        return $policyAbilityType;
    }

    public function abilities(Abilities $abilities): void
    {
        $this->abilityTypes = $this->abilityTypes->merge(
            $abilities->getAbilityTypes()
        );
    }

    public function getAbilityTypes(): Collection
    {
        return $this->abilityTypes;
    }
}