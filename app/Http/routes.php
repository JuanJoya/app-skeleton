<?php
/**
 * @file
 * Este archivo crea una instancia del RouteCollector en el cual se registran
 * las rutas de la aplicación.
 */

use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();

/**
 * ---------------------------------------------------------------------------------------
 * App Routes
 * ---------------------------------------------------------------------------------------
 * Cada ruta puede responder a diferentes verbos http (get|post|put|patch|delete):
 *     $router->any($route, $handler);
 *
 * Donde $route es un string con la url que deseamos atender y $handler puede ser un
 * callback o un array con el namespace y el método para llamar a una clase.
 *      $router->any('/users/{id}', ['Controllers\User','show']);
 *
 * Mas información en:
 * @see https://github.com/mrjgreen/phroute
 */

$router->get('/example', function () {
    response()->setContent('Hello World!');
});

$router->controller('/', App\Http\Controllers\HomeController::class);

return $router;
