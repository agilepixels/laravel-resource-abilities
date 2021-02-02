<?php

namespace AgilePixels\ResourceAbilities;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

trait HasRelationships
{
    public static function collectionWhenLoaded(string $relationship, JsonResource $jsonResource): AnonymousResourceCollection
    {
        return $jsonResource->resource->relationLoaded($relationship) ?
            static::collection($jsonResource->whenLoaded($relationship))->toResponse(null)->getData() :
            static::collection($jsonResource->whenLoaded($relationship));
    }

    public static function makeWhenLoaded(string $relationship, JsonResource $resource): static
    {
        return static::make($resource->whenLoaded($relationship));
    }
}
