<?php

namespace AgilePixels\ResourceAbilities\Tests;

use AgilePixels\ResourceAbilities\Abilities;
use AgilePixels\ResourceAbilities\AbilityTypes\GateAbilityType;
use AgilePixels\ResourceAbilities\AbilityTypes\PolicyAbilityType;
use AgilePixels\ResourceAbilities\Tests\Fakes\TestModel;
use AgilePixels\ResourceAbilities\Tests\Fakes\TestPolicy;

class AbilitiesTest extends TestCase
{
    /** @test */
    public function it_can_add_a_policy()
    {
        $abilities = new Abilities();

        $abilities->policy(TestPolicy::class, new TestModel());

        $this->assertTrue(
            $abilities
                ->getAbilityTypes()
                ->contains(PolicyAbilityType::make(TestPolicy::class, new TestModel()))
        );
    }

    public function it_can_add_a_gate()
    {
        $abilities = new Abilities();

        $abilities->gate(TestPolicy::CREATE, new TestModel());

        $this->assertTrue(
            $abilities
                ->getAbilityTypes()
                ->contains(GateAbilityType::make(TestPolicy::CREATE, new TestModel()))
        );
    }
}