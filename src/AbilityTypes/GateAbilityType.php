<?php

namespace AgilePixels\ResourceAbilities\AbilityTypes;

use AgilePixels\ResourceAbilities\AbilityContainer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use JetBrains\PhpStorm\Pure;

class GateAbilityType extends AbilityType
{
    #[Pure]
    public function __construct(protected string $ability, Model | string $model)
    {
        parent::__construct($model);
    }

    #[Pure]
    public static function make(string $ability, Model | string $model): GateAbilityType
    {
        return new self($ability, $model);
    }

    public function getAbilities(array $abilities, bool $withAllAbilities): array
    {
        if (!in_array($this->ability, $abilities, true) && ! $withAllAbilities) {
            return [];
        }

        $abilityContainer = AbilityContainer::make(
            $this->ability,
            Gate::check($this->ability, [$this->model, ...$this->parameters]),
        );

        return $this->resolveSerializer()->format($abilityContainer);
    }
}
