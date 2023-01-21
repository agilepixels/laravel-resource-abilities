<?php

namespace AgilePixels\ResourceAbilities\AbilityTypes;

use AgilePixels\ResourceAbilities\AbilityContainer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use JetBrains\PhpStorm\Pure;
use ReflectionMethod;

class PolicyAbilityType extends AbilityType
{
    protected string $policy;

    protected array $abilities = [];

    #[Pure]
    public function __construct(string $policy, Model | string $model)
    {
        parent::__construct($model);

        $this->policy = $policy;
        $this->abilities = get_class_methods($policy);
    }

    #[Pure]
    public static function make(string $policy, Model | string $model): static
    {
        return new static($policy, $model);
    }

    public function getAbilities(array $abilities, bool $withAllAbilities): array
    {
        return collect($this->abilities)

            /**
             * Filter out helper methods
             */
            ->reject(fn (string $ability) => in_array($ability, ['denyWithStatus', 'denyAsNotFound']))

            /**
             * Filter out the non-specified abilities
             */
            ->when(
                ! $withAllAbilities,
                fn (Collection $collection) => $collection->filter(fn (string $ability) => in_array($ability, $abilities, true))
            )

            /**
             * Filter out the methods that require a model if no model is available
             */
            ->when(
                ! $this->model instanceof Model,
                fn (Collection $collection) => $collection->filter(fn (string $ability) => ! $this->requiresModelInstance($this->policy, $ability))
            )

            /**
             * Authorize all abilities that are left against $this->model
             */
            ->map(
                fn ($ability) => is_string($this->model) || $this->requiresModelInstance($this->policy, $ability)
                ? AbilityContainer::make($ability, Gate::check($ability, [$this->model, ...$this->parameters]))
                : AbilityContainer::make($ability, Gate::check($ability, [$this->model::class, ...$this->parameters]))
            )

            /**
             * Format the resulting set of abilities using the given serializer
             */
            ->flatMap(fn ($abilityContainer) => $this->resolveSerializer()->format($abilityContainer))
            ->toArray();
    }

    protected function getParameters(string $policy, string $ability): Collection
    {
        return collect((new ReflectionMethod($policy, $ability))->getParameters());
    }

    protected function requiresModelInstance(string $policy, string $ability): bool
    {
        return $this->getParameters($policy, $ability)->skip(1)->first()?->getType()?->getName() === (is_string($this->model) ? $this->model : $this->model::class);
    }
}
