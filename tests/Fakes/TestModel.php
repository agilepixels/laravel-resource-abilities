<?php

namespace AgilePixels\ResourceAbilities\Tests\Fakes;

use AgilePixels\ResourceAbilities\HasAbilities;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasAbilities;

    public $timestamps = false;
}
