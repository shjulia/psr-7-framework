<?php

use App\Http\Action;
use App\Http\Middleware;
use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Configuration

$container = new \Framework\Container\Container();

$container->set('config', [
    'debug' => true,
    'users' => ['admin' => 'password'],
]);

$container->set(Middleware\BasicAuthMiddleware::class, function (Container $container) {
   return new Middleware\BasicAuthMiddleware($container->get('config')['users']);
});

$container->set(Middleware\ErrorHandlerMiddleware::class, function (Container $container) {
    return new Middleware\ErrorHandlerMiddleware($container->get('config')['debug']);
});

### Initialization

$aura = new Aura\Router\RouterContainer();

$routes = $aura->getMap();
$routes->get('home', '/', Action\HelloAction::class);
$routes->get('about', '/about', Action\AboutAction::class);
$routes->get('cabinet', '/cabinet', Action\CabinetAction::class);
$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);  //Оборачиваем AuraRouter в свой адаптер
$resolver = new MiddlewareResolver();

$app = new Application($resolver, new Middleware\NotFoundHandler(), new Response());
$app->pipe($container->get(Middleware\ErrorHandlerMiddleware::class));
$app->pipe(Middleware\CredentialsMiddleware::class);
$app->pipe(Middleware\ProfilerMiddleware::class);
$app->pipe(new Framework\Http\Middleware\RouteMiddleware($router));
$app->pipe('cabinet',$container->get(Middleware\BasicAuthMiddleware::class));
$app->pipe(new Framework\Http\Middleware\DispatchMiddleware($resolver)); //запускает наши экшены

### Running

$request = ServerRequestFactory::fromGlobals(); //содержит все информацию о запросе
$response = $app->run($request, new Response());

### Sending

//отправляем обратно в бразуер результат
$emitter = new SapiEmitter();
$emitter->emit($response);