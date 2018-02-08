<?php

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\RouteData;
use Framework\Http\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewarePipe;

class Application extends MiddlewarePipe
{
    public $resolver;
    private $default;
    private $router;

    public function __construct(
        MiddlewareResolver $resolver,
        Router $router,
        callable $default,
        ResponseInterface $responsePrototype
    )
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->router = $router;
        $this->setResponsePrototype($responsePrototype);
        $this->default = $default;
    }

    public function pipe($path, $middleware = null): MiddlewarePipe
    {
        if($middleware === null)
            return parent::pipe($this->resolver->resolve($path, $this->responsePrototype));

        return parent::pipe($this->resolver->resolve($middleware, $this->responsePrototype));
    }

    public function run(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this($request, $response, $this->default);
    }

    /** В $methods можно передать любой HTTP глагол*/
    public function route($name, $path, $handler, array $methods, array $options = []): void
    {
        $this->router->addRoute(new RouteData($name, $path, $handler, $methods, $options));
    }

    /** Доступен по любым методам */
    public function any($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, [], $options);
    }

    public function get($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, ['GET'], $options);
    }

    public function post($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, ['POST'], $options);
    }

    public function put($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, ['PUT'], $options);
    }

    public function patch($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, ['PATCH'], $options);
    }

    public function delete($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, ['DELETE'], $options);
    }
}