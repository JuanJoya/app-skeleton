<?php

/**
 * ---------------------------------------------------------------------------------------
 * En este archivo se registran las rutas de la Aplicación, cada ruta puede responder a
 * diferentes verbos HTTP, se admiten PSR-15 Middleware ya que el router también funciona
 * como dispatcher, cada ruta necesita un string con la url y un handler, este puede ser
 * un callback o un array con el namespace y el método para llamar a un Controller.
 * ---------------------------------------------------------------------------------------
 * @see https://route.thephpleague.com/4.x/routes/
 * @var League\Route\Router $router
 */

use League\Route\RouteGroup;
use App\Http\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);

$router->group('app', function (RouteGroup $route) {
    $route->get('/redirect', [HomeController::class, 'redirect']);
    $route->get('/data', [HomeController::class, 'data']);
    $route->get('/json', [HomeController::class, 'json']);
    $route->get('/session', [HomeController::class, 'session']);
    $route->get('/array-paginate', [HomeController::class, 'arrayPaginate']);
    $route->get('/orm-paginate', [HomeController::class, 'ormPaginate']);
    $route->get('/create', [HomeController::class, 'create']);
    $route->post('/create', [HomeController::class, 'store']);
});
