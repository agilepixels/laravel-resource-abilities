<?php

namespace AgilePixels\ResourceAbilities\ResourceCollection;

use AgilePixels\ResourceAbilities\AbilityResource;
use AgilePixels\ResourceAbilities\Collection;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @property-read Collection $resource
 */
trait ProcessesAbilities
{
    public function abilities(string $ability, string $model, array $parameters = [], string $serializer = null): AbilityResource
    {
        return AbilityResource::create(
            $model,
            $this->resource->getAbilities(),
            $this->resource->getWithAllAbilities()
        )->add($ability, $parameters, $serializer);
    }

    /**
     * Map the given collection resource into its individual resources.
     *
     * @param  mixed  $resource
     * @return mixed
     */
    protected function collectResource($resource)
    {
        if ($resource instanceof MissingValue) {
            return $resource;
        }

        if (is_array($resource)) {
            $resource = new Collection($resource);
        }

        $collects = $this->collects();

        $this->collection = $collects && ! $resource->first() instanceof $collects
            ? $resource->mapInto($collects)
            : $resource->toBase();

        return $resource instanceof AbstractPaginator
            ? $resource->setCollection($this->collection)
            : $this->collection;
    }
}
