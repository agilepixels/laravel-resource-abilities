<?php

namespace AgilePixels\ResourceAbilities;

use AgilePixels\ResourceAbilities\HasAbilities as ModelHasAbilities;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read Model|ModelHasAbilities $resource
 */
trait ProcessesAbilities
{
    /**
     * @param string $ability
     * @param array $parameters
     * @param string|null $serializer
     *
     * @return AbilityResource
     */
    public function abilities(string $ability, array $parameters = [], string $serializer = null): AbilityResource
    {
        return AbilityResource::create($this->resource, $this->resource->getAbilities())
            ->ability($ability, $parameters, $serializer);
    }
}
