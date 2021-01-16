<?php

namespace AgilePixels\ResourceAbilities\Tests;

use AgilePixels\ResourceAbilities\ProcessesAbilities;
use AgilePixels\ResourceAbilities\Serializers\ExtendedAbilitySerializer;
use AgilePixels\ResourceAbilities\Tests\Fakes\TestModel;
use AgilePixels\ResourceAbilities\Tests\Fakes\TestPolicy;
use AgilePixels\ResourceAbilities\Tests\Fakes\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProcessesAbilitiesTest extends TestCase
{
    private TestModel $testModel;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Gate::policy(TestModel::class, TestPolicy::class);

        $this->testModel = TestModel::create([
            'id' => 1,
            'name' => 'testModel',
        ]);
        $this->user = User::create([
            'id' => 1,
            'name' => 'Test User',
        ]);

        Auth::login($this->user);
    }

    /** @test */
    public function it_will_generate_abilities_when_making_a_resource()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'abilities' => $this->abilities(TestPolicy::class),
                ];
            }
        };

        $this->router->get('/resource', fn () => $testResource::make($this->testModel));

        $this->get('/resource')->assertExactJson([
            'data' => [
                'abilities' => [],
            ],
        ]);
    }

    /** @test */
    public function it_will_load_abilities()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'abilities' => $this->abilities(TestPolicy::class),
                ];
            }
        };

        $this->testModel
            ->addAbility('view')
            ->addAbility('update');

        $this->router->get('/resource', fn () => $testResource::make($this->testModel));

        $this->get('/resource')->assertExactJson([
            'data' => [
                'abilities' => [
                    'view' => true,
                    'update' => false,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_will_pass_parameters()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'abilities' => $this->abilities(TestPolicy::class, [true]),
                ];
            }
        };

        $this->testModel
            ->addAbility('restore');

        $this->router->get('/resource', fn () => $testResource::make($this->testModel));

        $this->get('/resource')->assertExactJson([
            'data' => [
                'abilities' => [
                    'restore' => true,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_will_use_serializer()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'abilities' => $this->abilities(TestPolicy::class, serializer: ExtendedAbilitySerializer::class),
                ];
            }
        };

        $this->testModel
            ->addAbility('view');

        $this->router->get('/resource', fn () => $testResource::make($this->testModel));

        $this->get('/resource')->assertExactJson([
            'data' => [
                'abilities' => [
                    'view' => [
                        'ability' => 'view',
                        'granted' => true,
                    ],
                ],
            ],
        ]);
    }
}