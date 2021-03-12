<?php

namespace AgilePixels\ResourceAbilities\Tests\ResourceCollection;

use AgilePixels\ResourceAbilities\ResourceCollection\ProcessesAbilities;
use AgilePixels\ResourceAbilities\ResourceCollection;
use AgilePixels\ResourceAbilities\Serializers\ExtendedAbilitySerializer;
use AgilePixels\ResourceAbilities\Tests\Fakes\TestModel;
use AgilePixels\ResourceAbilities\Tests\Fakes\TestPolicy;
use AgilePixels\ResourceAbilities\Tests\Fakes\User;
use AgilePixels\ResourceAbilities\Tests\TestCase;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProcessesAbilitiesTest extends TestCase
{
    private JsonResource $testResource;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Gate::policy(TestModel::class, TestPolicy::class);

        $this->testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [];
            }
        };
        $this->user = User::create([
            'id' => 1,
            'name' => 'Test User',
        ]);

        Auth::login($this->user);
    }

    /** @test */
    public function it_will_generate_abilities()
    {
        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => new class($collection) extends ResourceCollection {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'data' => $this->collection,
                    'abilities' => $this->abilities(TestPolicy::class, TestModel::class),
                ];
            }
        });

        $this->get('/resources')->assertExactJson([
            'data' => [],
            'abilities' => [
                'viewAny' => true,
                'create' => false,
            ],
        ]);
    }

    /** @test */
    public function it_will_check_policy_abilities()
    {
        $collection = TestModel::query()->get();
        $collection
            ->checkAbility('viewAny')
            ->checkAbility('create');

        $this->router->get('/resources', fn () => new class($collection) extends ResourceCollection {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'data' => $this->collection,
                    'abilities' => $this->abilities(TestPolicy::class, TestModel::class),
                ];
            }
        });

        $this->get('/resources')->assertExactJson([
            'data' => [],
            'abilities' => [
                'viewAny' => true,
                'create' => false,
            ],
        ]);
    }

    /** @test */
    public function it_will_check_gate_abilities()
    {
        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => new class($collection) extends ResourceCollection {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'data' => $this->collection,
                    'abilities' => $this->abilities('viewAny', TestModel::class),
                ];
            }
        });

        $this->get('/resources')->assertExactJson([
            'data' => [],
            'abilities' => [
                'viewAny' => true,
            ],
        ]);
    }

    /** @test */
    public function it_will_check_multiple_gate_abilities()
    {
        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => new class($collection) extends ResourceCollection {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'data' => $this->collection,
                    'abilities' => $this->abilities('viewAny', TestModel::class)->add('create'),
                ];
            }
        });

        $this->get('/resources')->assertExactJson([
            'data' => [],
            'abilities' => [
                'viewAny' => true,
                'create' => false,
            ],
        ]);
    }

    /** @test */
    public function it_will_check_only_policy_abilities_without_models()
    {
        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => new class($collection) extends ResourceCollection {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'data' => $this->collection,
                    'abilities' => $this->abilities(TestPolicy::class, TestModel::class),
                ];
            }
        });

        $this->get('/resources')->assertExactJson([
            'data' => [],
            'abilities' => [
                'viewAny' => true,
                'create' => false,
            ],
        ]);
    }

    /** @test */
    public function it_will_pass_parameters_when_checking_gates()
    {
        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => new class($collection) extends ResourceCollection {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'data' => $this->collection,
                    'abilities' => $this->abilities('create', TestModel::class, [true]),
                ];
            }
        });

        $this->get('/resources')->assertExactJson([
            'data' => [],
            'abilities' => [
                'create' => true,
            ],
        ]);
    }

    /** @test */
    public function it_will_pass_parameters_when_checking_policies()
    {
        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => new class($collection) extends ResourceCollection {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'data' => $this->collection,
                    'abilities' => $this->abilities(TestPolicy::class, TestModel::class, [true]),
                ];
            }
        });

        $this->get('/resources')->assertExactJson([
            'data' => [],
            'abilities' => [
                'viewAny' => true,
                'create' => true,
            ],
        ]);
    }

    /** @test */
    public function it_will_use_serializer_when_checking_policies()
    {
        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => new class($collection) extends ResourceCollection {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'data' => $this->collection,
                    'abilities' => $this->abilities(TestPolicy::class, TestModel::class, serializer: ExtendedAbilitySerializer::class),
                ];
            }
        });

        $this->get('/resources')->assertExactJson([
            'data' => [],
            'abilities' => [
                'viewAny' => [
                    'ability' => 'viewAny',
                    'granted' => true,
                ],
                'create' => [
                    'ability' => 'create',
                    'granted' => false,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_will_use_serializer_when_checking_gates()
    {
        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => new class($collection) extends ResourceCollection {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'data' => $this->collection,
                    'abilities' => $this->abilities('viewAny', TestModel::class, serializer: ExtendedAbilitySerializer::class),
                ];
            }
        });

        $this->get('/resources')->assertExactJson([
            'data' => [],
            'abilities' => [
                'viewAny' => [
                    'ability' => 'viewAny',
                    'granted' => true,
                ],
            ],
        ]);
    }
}
