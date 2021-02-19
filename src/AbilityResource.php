<?php

namespace AgilePixels\ResourceAbilities;

use AgilePixels\ResourceAbilities\AbilityTypes\AbilityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AbilityResource extends JsonResource
{
    /** The abilities that are available in the resource */
    protected Abilities $abilitiesGroup;

    protected array $abilities;

    protected bool $withAllAbilities;

    public function __construct(Model | string $model, array $abilities, bool $withAllAbilities)
    {
        parent::__construct($model);

        $this->abilitiesGroup = new Abilities();
        $this->abilities = $abilities;
        $this->withAllAbilities = $withAllAbilities;
    }

    public static function create(Model | string $model, array $abilities, bool $withAllAbilities): static
    {
        return new static($model, $abilities, $withAllAbilities);
    }

    public function add(string $ability, array $parameters = [], string $serializer = null): AbilityResource
    {
        /**
         * Handle Policy check
         */
        if (Str::endsWith($ability, 'Policy')) {
            $this->abilitiesGroup
                ->policy($ability, $this->resource)
                ->parameters($parameters)
                ->serializer($serializer);

            return $this;
        }

        /**
         * Handle Gate check
         */
        $this->abilitiesGroup
            ->gate($ability, $this->resource)
            ->parameters($parameters)
            ->serializer($serializer);

        return $this;
    }

    public function toArray($request): array
    {
        return $this->abilitiesGroup
            ->getAbilityTypes()
            ->mapWithKeys(function (AbilityType $abilityType) {
                return $abilityType->getAbilities($this->abilities, $this->withAllAbilities);
            })
            ->toArray();
    }
}
