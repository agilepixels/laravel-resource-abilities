<?php

namespace AgilePixels\ResourceAbilities\Resources;

use AgilePixels\ResourceAbilities\AbilityResource;
use AgilePixels\ResourceAbilities\Models\HasAbilities as ModelHasAbilities;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read Model|ModelHasAbilities $resource
 */
trait HasAbilities
{
    /**
     * @param string $policy
     * @param array $parameters
     * @param string|null $serializer
     *
     * @return AbilityResource
     */
    public function abilities(string $policy, array $parameters = [], string $serializer = null): AbilityResource
    {
        return AbilityResource::create($this->resource, $this->resource->getAbilities())
            ->ability($policy, $parameters, $serializer);
    }
}
