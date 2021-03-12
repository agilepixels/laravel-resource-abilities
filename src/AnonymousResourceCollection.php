<?php

namespace AgilePixels\ResourceAbilities;

class AnonymousResourceCollection extends ResourceCollection
{
    /**
     * Create a new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @param  string  $collects
     * @return void
     */
    public function __construct($resource, public $collects)
    {
        parent::__construct($resource);
    }
}
