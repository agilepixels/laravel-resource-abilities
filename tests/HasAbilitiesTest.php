<?php

namespace AgilePixels\ResourceAbilities\Tests;

use AgilePixels\ResourceAbilities\Tests\Fakes\TestModel;

class HasAbilitiesTest extends TestCase
{
    use AddsAbilitiesTest;

    private TestModel $testModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testModel = TestModel::create([
            'id' => 1,
            'name' => 'testModel',
        ]);
    }
}
