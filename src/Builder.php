<?php


namespace AgilePixels\ResourceAbilities;

use Illuminate\Database\Eloquent\Builder as BaseBuilder;

/**
 * @mixin BaseBuilder
 * @method Builder addAbility(string $ability)
 * @method Builder withAllAbilities(bool $withAllAbilities = true)
 * @method Collection get($columns = ['*'])
 */
class Builder extends BaseBuilder
{
    //
}
