<?php

namespace AgilePixels\ResourceAbilities\Tests\Models;

use AgilePixels\ResourceAbilities\Tests\Fakes\TestModel;
use AgilePixels\ResourceAbilities\Tests\TestCase;

class HasAbilitiesTest extends TestCase
{
    private TestModel $testModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testModel = TestModel::create([
            'id' => 1,
            'name' => 'testModel',
        ]);
    }

    /** @test */
    public function it_returns_abilities()
    {
        $this->assertEmpty($this->testModel->getAbilities());
    }

    /** @test */
    public function it_adds_abilities()
    {
        $this->testModel->addAbility('ability');

        $this->assertContains('ability', $this->testModel->getAbilities());
    }
}