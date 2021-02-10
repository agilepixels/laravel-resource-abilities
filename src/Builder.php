<?php


namespace AgilePixels\ResourceAbilities;

use Illuminate\Database\Eloquent\Builder as BaseBuilder;

/**
 * @mixin BaseBuilder
 * @method Builder addAbility(string $ability)
 * @method Collection get($columns = ['*'])
 */
class Builder extends BaseBuilder
{
    //
}
