<?php

namespace AgilePixels\ResourceAbilities\AbilityTypes;

use AgilePixels\ResourceAbilities\Serializers\Serializer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

abstract class AbilityType
{
    protected array $parameters = [];

    protected ?string $serializer = null;

    public function __construct(protected Model | string $model)
    {
    }

    /**
     * Parameters that are passed to the policy method which can be used for additional
     * context when making authorization decisions.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function parameters(array $parameters): self
    {
        foreach ($parameters as $parameter) {
            $this->parameters = array_merge($this->parameters, Arr::wrap($parameter));
        }

        return $this;
    }

    /**
     * The serializer to format the output of abilities. By default, a config value
     * in resource-abilities.serializer will be used.
     *
     * @param string|null $serializer
     *
     * @return $this
     */
    public function serializer(?string $serializer): self
    {
        $this->serializer = $serializer;

        return $this;
    }

    protected function resolveSerializer(): Serializer
    {
        $serializer = is_null($this->serializer)
            ? config('resource-abilities.serializer')
            : $this->serializer;

        return new $serializer;
    }

    /**
     * @param bool $withAllAbilities
     * @param array $abilities An array of abilities that should be loaded for the resource
     *
     * @return array
     */
    abstract public function getAbilities(array $abilities, bool $withAllAbilities): array;
}
