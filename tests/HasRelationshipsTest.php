<?php

namespace AgilePixels\ResourceAbilities\Tests;

use AgilePixels\ResourceAbilities\HasRelationships;
use AgilePixels\ResourceAbilities\JsonResource\ProcessesAbilities;
use AgilePixels\ResourceAbilities\Tests\Fakes\TestModel;
use AgilePixels\ResourceAbilities\Tests\Fakes\User;
use AgilePixels\ResourceAbilities\Tests\Fakes\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class HasRelationshipsTest extends TestCase
{
    private TestModel $testModel;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

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
    public function it_will_drop_a_to_one_relation_when_not_loaded()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities, HasRelationships;

            public function toArray($request)
            {
                return [
                    'user' => UserResource::makeWhenLoaded('user', $this),
                ];
            }
        };

        $this->router->get('/resource', fn () => $testResource::make($this->testModel));

        $this->get('/resource')->assertExactJson([
            'data' => [],
        ]);
    }

    /** @test */
    public function it_will_add_a_to_one_relation_when_loaded()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities, HasRelationships;

            public function toArray($request)
            {
                return [
                    'user' => UserResource::makeWhenLoaded('user', $this),
                ];
            }
        };

        $this->testModel->setRelation('user', $this->user);

        $this->router->get('/resource', fn () => $testResource::make($this->testModel));

        $this->get('/resource')->assertExactJson([
            'data' => [
                'user' => [
                    'id' => 1,
                    'name' => 'Test User',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_will_drop_a_to_many_relation_when_not_loaded()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities, HasRelationships;

            public function toArray($request)
            {
                return [
                    'users' => UserResource::collectionWhenLoaded('users', $this),
                ];
            }
        };

        $this->router->get('/resource', fn () => $testResource::make($this->testModel));

        $this->get('/resource')->assertExactJson([
            'data' => [],
        ]);
    }

    /** @test */
    public function it_will_add_a_to_many_relation_when_loaded()
    {
        $testResource = new class(null) extends JsonResource {
            use ProcessesAbilities, HasRelationships;

            public function toArray($request)
            {
                return [
                    'users' => UserResource::collectionWhenLoaded('users', $this),
                ];
            }
        };

        $this->testModel->setRelation('users', [$this->user]);

        $this->router->get('/resource', fn () => $testResource::make($this->testModel));

        $this->get('/resource')->assertExactJson([
            'data' => [
                'users' => [
                    [
                        'id' => 1,
                        'name' => 'Test User',
                    ],
                ],
            ],
        ]);
    }
}
