<?php

namespace AgilePixels\ResourceAbilities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class ResourceAbilitiesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/resource-abilities.php' => config_path('resource-abilities.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/resource-abilities.php', 'resource-abilities');

        Builder::macro('checkAbility', function (string $ability) {
            $this->model->checkAbility($ability);

            return $this;
        });

        Builder::macro('withAllAbilities', function (bool $withAllAbilities = true) {
            $this->model->withAllAbilities($withAllAbilities);

            return $this;
        });
    }
}
