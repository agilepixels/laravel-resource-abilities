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

    public function __construct(Model $model = null, protected array $abilities = [])
    {
        parent::__construct($model);

        $this->abilitiesGroup = new Abilities();
    }

    public static function create(Model $model = null, array $abilities = []): self
    {
        return new self($model, $abilities);
    }

    public function ability(string $ability, array $parameters = [], string $serializer = null): AbilityResource
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
                return $abilityType->getAbilities($this->abilities);
            })
            ->toArray();
    }
}