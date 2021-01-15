<?php

namespace AgilePixels\ResourceAbilities\Tests\Fakes;

use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;
use JetBrains\PhpStorm\Pure;

class TestRouter
{
    private RouteCollection $routeCollection;

    #[Pure]
    public function __construct(private Router $router)
    {
        $this->routeCollection = new RouteCollection();
    }

    public static function setup(): TestRouter
    {
        return new self(app(Router::class));
    }

    public function route($methods, $uri, $action): Route
    {
        $route = new Route($methods, $uri, $action);

        $this->addRoute($route->middleware('web'));

        return $route;
    }

    public function get($uri, $action): Route
    {
        return $this->route('GET', $uri, $action);
    }

    public function post($uri, $action): Route
    {
        return $this->route('POST', $uri, $action);
    }

    public function put($uri, $action): Route
    {
        return $this->route('PUT', $uri, $action);
    }

    private function addRoute(Route $route)
    {
        $this->routeCollection->add($route);

        $this->router->setRoutes($this->routeCollection);
    }
}
