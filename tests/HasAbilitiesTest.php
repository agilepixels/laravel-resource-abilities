<?php

namespace AgilePixels\ResourceAbilities\Tests;

use AgilePixels\ResourceAbilities\Tests\Fakes\TestModel;

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
        $this->testModel->checkAbility('ability');

        $this->assertContains('ability', $this->testModel->getAbilities());
    }

    /** @test */
    public function it_merges_abilities_to_new_instances()
    {
        $this->testModel->checkAbility('ability');

        $newInstance = $this->testModel->newInstance();

        $this->assertContains('ability', $newInstance->getAbilities());
    }

    /** @test */
    public function it_adds_an_array_of_abilities()
    {
        $this->testModel->checkAbility(['ability', 'another_ability']);

        $this->assertContains('ability', $this->testModel->getAbilities());
        $this->assertContains('another_ability', $this->testModel->getAbilities());
    }

    /** @test */
    public function it_adds_spreaded_abilities()
    {
        $this->testModel->checkAbility('ability', 'another_ability');

        $this->assertContains('ability', $this->testModel->getAbilities());
        $this->assertContains('another_ability', $this->testModel->getAbilities());
    }
}
