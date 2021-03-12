<?php

namespace AgilePixels\ResourceAbilities;

use AgilePixels\ResourceAbilities\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

trait HasRelationships
{
    public static function collectionWhenLoaded(string $relationship, JsonResource $jsonResource): ResourceCollection
    {
        return static::collection($jsonResource->whenLoaded($relationship));
    }

    public static function makeWhenLoaded(string $relationship, JsonResource $resource): static
    {
        return static::make($resource->whenLoaded($relationship));
    }
}
