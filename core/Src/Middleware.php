<?php
namespace Src;

use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use FastRoute\DataGenerator\MarkBased;
use FastRoute\Dispatcher\MarkBased as Dispatcher;
use Src\Traits\SingletonTrait;

class Middleware
{
    use SingletonTrait;

    private RouteCollector $middlewareCollector;

    public function add($httpMethod, string $route, array $action): void
    {
        $this->middlewareCollector->addRoute($httpMethod, $route, $action);
    }

    public function group(string $prefix, callable $callback): void
    {
        $this->middlewareCollector->addGroup($prefix, $callback);
    }

    private function __construct()
    {
        $this->middlewareCollector = new RouteCollector(new Std(), new MarkBased());
    }

    public function go(string $httpMethod, string $uri, Request $request): Request
    {
        return $this->runMiddlewares(
            $httpMethod,
            $uri,
            $this->runAppMiddlewares($request)
        );
    }

    private function runMiddlewares(string $httpMethod, string $uri, Request $request): Request
    {
        $routeMiddleware = app()->settings->app['routeMiddleware'];

        foreach ($this->getMiddlewaresForRoute($httpMethod, $uri) as $middleware) {
            $args = explode(':', $middleware);
            $result = (new $routeMiddleware[$args[0]])->handle($request, $args[1] ?? null);
            $request = $result ?? $request;
        }

        return $request;
    }

    private function runAppMiddlewares(Request $request): Request
    {
        $routeMiddleware = app()->settings->app['routeAppMiddleware'] ?? [];

        foreach ($routeMiddleware as $name => $class) {
            $args = explode(':', $name);
            $result = (new $class)->handle($request, $args[1] ?? null);
            $request = $result ?? $request;
        }

        return $request;
    }

    private function getMiddlewaresForRoute(string $httpMethod, string $uri): array
    {
        $dispatcher = new Dispatcher($this->middlewareCollector->getData());
        return $dispatcher->dispatch($httpMethod, $uri)[1] ?? [];
    }
}