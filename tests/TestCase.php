<?php

namespace AgilePixels\ResourceAbilities\Tests;

use AgilePixels\ResourceAbilities\ResourceAbilitiesServiceProvider;
use AgilePixels\ResourceAbilities\Serializers\AbilitySerializer;
use AgilePixels\ResourceAbilities\Tests\Fakes\TestRouter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected TestRouter $router;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpEnvironment();
        $this->setUpDatabase();

        $this->router = TestRouter::setup();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ResourceAbilitiesServiceProvider::class,
        ];
    }

    private function setUpDatabase()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });

        Schema::create('test_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });

        Schema::create('second_test_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });
    }

    private function setUpEnvironment(): void
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config()->set('app.key', 'kuFyUdCwrgWJjLWURIbkemJlFLGatcmo');

        config()->set('resource-links.serializer', AbilitySerializer::class);

        Model::unguard();
    }
}
