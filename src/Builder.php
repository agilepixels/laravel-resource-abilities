<?php

namespace AgilePixels\ResourceAbilities;

use Illuminate\Database\Eloquent\Builder as BaseBuilder;

/**
 * @mixin BaseBuilder
 * @method Builder checkAbility(string $ability)
 * @method Builder withAllAbilities(bool $withAllAbilities = true)
 * @method Collection get($columns = ['*'])
 */
class Builder extends BaseBuilder
{
    //
}
