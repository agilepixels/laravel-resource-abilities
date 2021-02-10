<?php

namespace AgilePixels\ResourceAbilities;

use AgilePixels\ResourceAbilities\HasAbilities as ModelHasAbilities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\MissingValue;

/**
 * @property-read Model|ModelHasAbilities $resource
 */
trait ProcessesAbilities
{
    public function abilities(string $ability, array $parameters = [], string $serializer = null): AbilityResource
    {
        return AbilityResource::create($this->resource, $this->resource->getAbilities())
            ->ability($ability, $parameters, $serializer);
    }

    public static function collectionAbilities(Collection | MissingValue $resource, string $ability, string $model, array $parameters = [], string $serializer = null): AbilityResource
    {
        return AbilityResource::create(
            $model,
            $resource instanceof Collection ? $resource->getAbilities() : []
        )->ability($ability, $parameters, $serializer);
    }
}
