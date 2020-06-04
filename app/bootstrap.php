<?php

/**
 * @internal
 * Este archivo contiene toda la lógica del front-end controller.
 */

declare(strict_types=1);

/*
 * El namespace de la aplicación, coincide con la definición del archivo composer.json
 */
namespace App;

use App\Core\Router\RouterResolver;
use Dotenv\Dotenv;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;

/**
 * @var string ROOT_PATH directorio raíz de la aplicación.
 */
define('ROOT_PATH', dirname(__DIR__));

/**
 * @var string URL absoluta al directorio 'public'.
 */
define('URL', str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']));

/**
 * @var string URI
 */
define('URI', str_replace(URL, '', strtok($_SERVER['REQUEST_URI'], '?')));

/**
 * Permite establecer la zona horaria de la aplicación.
 */
date_default_timezone_set('America/Bogota');

/*
 * ---------------------------------------------------------------------------------------
 * PSR-4 Autoload
 * ---------------------------------------------------------------------------------------
 * Describe un estándar de autocarga de nuestras clases, ya que composer realiza este
 * proceso por nosotros, solo es necesario setear el namespace en el archivo composer.json
 * y requerir el autoload.
 */
require_once ROOT_PATH . '/vendor/autoload.php';

/*
 * ---------------------------------------------------------------------------------------
 * PHP DotEnv
 * ---------------------------------------------------------------------------------------
 * Se considera una mala practica almacenar credenciales sensibles en nuestro código
 * lo ideal es utilizar variables de entorno, solo hace falta indicar la ruta
 * al archivo .env el cual almacena dichas variables.
 */
$dotEnv = Dotenv::createImmutable(ROOT_PATH);
$dotEnv->load();
$dotEnv->required(['APP_DEBUG'])->isBoolean();

/*
 * ---------------------------------------------------------------------------------------
 * Error Handler
 * ---------------------------------------------------------------------------------------
 * Registrar un manejador de errores en nuestra aplicación es util ya que captura todos
 * los errores-excepciones que no estén contemplados en un bloque tryCatch, nos indica de
 * forma amigable el stack del error e incluso resalta el código con el error, obviamente
 * solo se recomienda en desarrollo, es necesario deshabilitarlo en producción.
 */
/**
 * @type \Whoops\Run $errorHandler
 */
$errorHandler = require __DIR__ . '/Core/errorHandler.php';
$errorHandler->register();

/*
 * ---------------------------------------------------------------------------------------
 * IoC Dependency Injector
 * ---------------------------------------------------------------------------------------
 * Uno de los principios de SOLID es la inversion de control, en pocas palabras se refiere
 * a delegar la responsabilidad que tiene una clase de crear sus dependencias a una
 * 'herramienta' externa, esto lo logramos con la inyección de dependencias. El archivo
 * container.php configura y retorna una instancia de esta herramienta.
 */
/**
 * @type \Auryn\Injector $injector
 */
$injector = require __DIR__ . '/Core/container.php';

/*
 * ---------------------------------------------------------------------------------------
 * Database ORM
 * ---------------------------------------------------------------------------------------
 * Object-Relational-Mapping es una técnica de programación que convierte las tablas de
 * una base de datos relacional en entidades para trabajar en un modelo de datos orientado
 * a objetos. El archivo database.php crea una instancia del ORM y registra los datos (dsn)
 * de conexión.
 */
/**
 * @type \Illuminate\Database\Capsule\Manager $database
 */
$database = require __DIR__ . '/Core/database.php';
$database->bootEloquent();

/*
 * ---------------------------------------------------------------------------------------
 * Router - Route Collector
 * ---------------------------------------------------------------------------------------
 * Este archivo incluye la definición de todas las rutas de nuestra aplicación, estas
 * rutas responden a diferentes verbos http (get|post|put|delete), admiten parámetros
 * en la url, aplican filtros (before-after) y finalmente registran un callback o una
 * clase (controller).
 */
/**
 * @type \Phroute\Phroute\RouteCollector $router
 */
$router = require __DIR__ . '/Http/routes.php';

/*
 * ---------------------------------------------------------------------------------------
 * Router - Router Resolver
 * ---------------------------------------------------------------------------------------
 * El router internamente construye una instancia de la clase (controller) que registramos
 * en la ruta correspondiente pero no resuelve sus dependencias, esto es responsabilidad
 * de nuestro $injector, el Router Resolver nos permite implementar el contenedor IoC para
 * resolver dichas dependencias.
 */
$resolver = new RouterResolver($injector);

/*
 * ---------------------------------------------------------------------------------------
 * Router - Dispatcher
 * ---------------------------------------------------------------------------------------
 * El objeto Dispatcher utiliza la definición de las rutas del Route Collector y el
 * resolver para realmente generar el recurso, esto se ejecuta en el método dispatch.
 */
$dispatcher = new Dispatcher($router->getData(), $resolver);

/*
 * ---------------------------------------------------------------------------------------
 * HTTP Request & Response
 * ---------------------------------------------------------------------------------------
 * Es un estándar en la mayoría de frameworks utilizar una definición de Request y
 * Response, básicamente son dos objetos que almacenan información sobre el flujo
 * natural de una aplicación web.
 */
/**
 * @type \Http\Request $request
 * @type \Http\Response $response
 */
$request  = $injector->make('Http\HttpRequest');
$response = $injector->make('Http\HttpResponse');

/*
 * ---------------------------------------------------------------------------------------
 * URL Rewrite
 * ---------------------------------------------------------------------------------------
 * El parámetro GET url proviene de la regla que definimos en el ModRewrite de apache
 * (.htaccess), es necesaria para el dispatch de la ruta respectiva.
 */
$uri = cleanUrl($_GET['uri'] ?? URI);

/*
 * ---------------------------------------------------------------------------------------
 * Dispatching Route
 * ---------------------------------------------------------------------------------------
 * El método dispatch ejecuta la ruta y se encarga de la respuesta, hace un llamado del
 * classMethod|Callback correspondiente y retorna el Response. Pueden existir dos estados
 * de error, cuando la ruta no existe en el RouteCollector o cuando existe pero el
 * requestMethod no corresponde al que se registro previamente, ambos estados lanzan
 * excepciones que podemos controlar.
 */
try {
    $dispatcher->dispatch($request->getMethod(), $uri);
} catch (HttpRouteNotFoundException $e) {
    $response->setContent(abort('404'));
    $response->setStatusCode(404);
} catch (HttpMethodNotAllowedException $e) {
    $response->setContent(abort('405'));
    $response->setStatusCode(405);
}

/*
 * Se determina si en algún punto de la aplicación se han modificado las cabeceras
 * Http, un ejemplo puede ser un var_dump().
 */
if (!headers_sent()) {
    /*
     * Este helper setea los HttpHeaders que incluye el objeto Response.
     */
    foreach ($response->getHeaders() as $header) {
        header($header, false);
    }
    /*
     * El método getContent retorna la salida final al navegador, un string con el html
     * procesado por el templateEngine e incluso una salida tipo Json.
     */
    echo $response->getContent();
} else {
    die();
}
