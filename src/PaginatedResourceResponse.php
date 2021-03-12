<?php

namespace AgilePixels\ResourceAbilities;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PaginatedResourceResponse extends ResourceResponse
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
            array_merge_recursive(
                $this->resource->resolve($request),
                $this->paginationInformation($request),
            ),
            $this->calculateStatus()
        ), function ($response) use ($request) {
            $response->original = $this->resource->resource->map(function ($item) {
                return is_array($item) ? Arr::get($item, 'resource') : $item->resource;
            });

            $this->resource->withResponse($request, $response);
        });
    }

    /**
     * Add the pagination information to the response.
     *
     * @param  Request  $request
     * @return array
     */
    protected function paginationInformation(Request $request): array
    {
        $paginated = $this->resource->resource->toArray();

        return [
            'links' => $this->paginationLinks($paginated),
            'meta' => $this->meta($paginated),
        ];
    }

    /**
     * Get the pagination links for the response.
     *
     * @param  array  $paginated
     * @return array
     */
    protected function paginationLinks(array $paginated): array
    {
        return [
            'first' => $paginated['first_page_url'] ?? null,
            'last' => $paginated['last_page_url'] ?? null,
            'prev' => $paginated['prev_page_url'] ?? null,
            'next' => $paginated['next_page_url'] ?? null,
        ];
    }

    /**
     * Gather the meta data for the response.
     *
     * @param  array  $paginated
     * @return array
     */
    protected function meta(array $paginated): array
    {
        return Arr::except($paginated, [
            'data',
            'first_page_url',
            'last_page_url',
            'prev_page_url',
            'next_page_url',
        ]);
    }
}
