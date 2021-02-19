<?php

namespace AgilePixels\ResourceAbilities\Tests;

use AgilePixels\ResourceAbilities\ProcessesAbilities;
use AgilePixels\ResourceAbilities\Serializers\ExtendedAbilitySerializer;
use AgilePixels\ResourceAbilities\Tests\Fakes\TestModel;
use AgilePixels\ResourceAbilities\Tests\Fakes\TestPolicy;
use AgilePixels\ResourceAbilities\Tests\Fakes\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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

        $this->router->get('/resource', fn () => $testResource::make($this->testModel->withAllAbilities(false)));

        $this->get('/resource')->assertExactJson([
            'data' => [
                'abilities' => [],
            ],
        ]);
    }

    /** @test */
    public function it_will_check_policy_abilities_when_making_a_resource()
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
    public function it_will_check_gate_abilities_when_making_a_resource()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'abilities' => $this->abilities('view'),
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
                ],
            ],
        ]);
    }

    /** @test */
    public function it_will_check_all_policy_abilities_when_making_a_resource()
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

        $this->router->get('/resource', fn () => $testResource::make($this->testModel));

        $this->get('/resource')->assertExactJson([
            'data' => [
                'abilities' => [
                    'viewAny' => true,
                    'view' => true,
                    'create' => true,
                    'update' => false,
                    'delete' => true,
                    'restore' => true,
                    'forceDelete' => false,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_will_pass_parameters_when_checking_gates_when_making_a_resource()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'abilities' => $this->abilities('restore', [true]),
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
    public function it_will_pass_parameters_when_checking_policies_when_making_a_resource()
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
    public function it_will_use_serializer_when_checking_policies_when_making_a_resource()
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

    /** @test */
    public function it_will_use_serializer_when_checking_gates_when_making_a_resource()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [
                    'abilities' => $this->abilities('view', serializer: ExtendedAbilitySerializer::class),
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

    /**
     * Collection
     */

    /** @test */
    public function it_will_generate_abilities_when_making_a_collection()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [];
            }

            public static function collection($resource): AnonymousResourceCollection
            {
                return parent::collection($resource)->additional([
                    'abilities' => self::collectionAbilities($resource, TestPolicy::class, TestModel::class),
                ]);
            }
        };

        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => $testResource::collection($collection->withAllAbilities(false)));

        $this->get('/resources')->assertExactJson([
            'data' => [[]],
            'abilities' => [],
        ]);
    }

    /** @test */
    public function it_will_check_policy_abilities_when_making_a_collection()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [];
            }

            public static function collection($resource): AnonymousResourceCollection
            {
                return parent::collection($resource)->additional([
                    'abilities' => self::collectionAbilities($resource, TestPolicy::class, TestModel::class),
                ]);
            }
        };

        $collection = TestModel::query()->get();
        $collection
            ->addAbility('viewAny')
            ->addAbility('create');

        $this->router->get('/resources', fn () => $testResource::collection($collection));

        $this->get('/resources')->assertExactJson([
            'data' => [[]],
            'abilities' => [
                'viewAny' => true,
                'create' => false,
            ],
        ]);
    }

    /** @test */
    public function it_will_check_gate_abilities_when_making_a_collection()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [];
            }

            public static function collection($resource): AnonymousResourceCollection
            {
                return parent::collection($resource)->additional([
                    'abilities' => self::collectionAbilities($resource, 'viewAny', TestModel::class),
                ]);
            }
        };

        $collection = TestModel::query()->get();
        $collection->addAbility('viewAny');

        $this->router->get('/resources', fn () => $testResource::collection($collection));

        $this->get('/resources')->assertExactJson([
            'data' => [[]],
            'abilities' => [
                'viewAny' => true,
            ],
        ]);
    }

    /** @test */
    public function it_will_check_only_policy_abilities_without_models_when_making_a_collection()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [];
            }

            public static function collection($resource): AnonymousResourceCollection
            {
                return parent::collection($resource)->additional([
                    'abilities' => self::collectionAbilities($resource, TestPolicy::class, TestModel::class),
                ]);
            }
        };

        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => $testResource::collection($collection));

        $this->get('/resources')->assertExactJson([
            'data' => [[]],
            'abilities' => [
                'viewAny' => true,
                'create' => false,
            ],
        ]);
    }

    /** @test */
    public function it_will_pass_parameters_when_checking_gates_when_making_a_collection()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [];
            }

            public static function collection($resource): AnonymousResourceCollection
            {
                return parent::collection($resource)->additional([
                    'abilities' => self::collectionAbilities($resource, 'create', TestModel::class, [true]),
                ]);
            }
        };

        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => $testResource::collection($collection));

        $this->get('/resources')->assertExactJson([
            'data' => [[]],
            'abilities' => [
                'create' => true,
            ],
        ]);
    }

    /** @test */
    public function it_will_pass_parameters_when_checking_policies_when_making_a_collection()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities;

            public function toArray($request)
            {
                return [];
            }

            public static function collection($resource): AnonymousResourceCollection
            {
                return parent::collection($resource)->additional([
                    'abilities' => self::collectionAbilities($resource, TestPolicy::class, TestModel::class, [true]),
                ]);
            }
        };

        $collection = TestModel::query()->get();

        $this->router->get('/resources', fn () => $testResource::collection($collection));

        $this->get('/resources')->assertExactJson([
            'data' => [[]],
            'abilities' => [
                'viewAny' => true,
                'create' => true,
            ],
        ]);
    }
}
