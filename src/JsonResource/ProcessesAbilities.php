<?php

namespace AgilePixels\ResourceAbilities\JsonResource;

use AgilePixels\ResourceAbilities\AbilityResource;
use AgilePixels\ResourceAbilities\AnonymousResourceCollection;
use AgilePixels\ResourceAbilities\Collection;
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
        return AbilityResource::create(
            $this->resource,
            $this->resource->getAbilities(),
            $this->resource->getWithAllAbilities()
        )->add($ability, $parameters, $serializer);
    }

    public static function collectionAbilities(Collection | MissingValue $resource, string $ability, string $model, array $parameters = [], string $serializer = null): AbilityResource
    {
        return AbilityResource::create(
            $model,
            $resource instanceof Collection ? $resource->getAbilities() : [],
            $resource instanceof Collection ? $resource->getWithAllAbilities() : true,
        )->add($ability, $parameters, $serializer);
    }

    /**
     * Create a new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @return AnonymousResourceCollection
     */
    public static function collection($resource): AnonymousResourceCollection
    {
        return tap(new AnonymousResourceCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static())->preserveKeys === true;
            }
        });
    }
}
