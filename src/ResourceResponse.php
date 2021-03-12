<?php

namespace AgilePixels\ResourceAbilities;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceResponse as Response;

class ResourceResponse extends Response
{
    /**
     * Create an HTTP response that represents the object. We make sure the returned array from the resolve() method
     * isn't wrapped again since we moved the wrapping logic to the resource instead of this response class.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return tap(response()->json(
            $this->resource->resolve($request),
            $this->calculateStatus()
        ), function ($response) use ($request) {
            $response->original = $this->resource->resource;

            $this->resource->withResponse($request, $response);
        });
    }
}
