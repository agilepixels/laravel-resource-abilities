<?php

namespace AgilePixels\ResourceAbilities\AbilityTypes;

use AgilePixels\ResourceAbilities\AbilityContainer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use JetBrains\PhpStorm\Pure;

class PolicyAbilityType extends AbilityType
{
    protected array $abilities = [];

    #[Pure]
    public function __construct(string $policy, Model $model)
    {
        parent::__construct($model);

        $this->abilities = get_class_methods($policy);
    }

    #[Pure]
    public static function make(string $policy, Model $model): PolicyAbilityType
    {
        return new self($policy, $model);
    }

    public function getAbilities(array $abilities): array
    {
        return collect($this->abilities)
            ->filter(fn ($ability) => in_array($ability, $abilities))
            ->map(fn ($ability) => AbilityContainer::make(
                $ability,
                Gate::check($ability, [$this->model, ...$this->parameters]),
            ))
            ->flatMap(fn ($abilityContainer) => $this->resolveSerializer()->format($abilityContainer))
            ->toArray();
    }
}
