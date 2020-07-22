<?php

/**
 * Este archivo contiene toda la lógica del front-end controller.
 */

declare(strict_types=1);

/**
 * @var string ROOT_PATH directorio raíz de la aplicación.
 */
define('ROOT_PATH', dirname(__DIR__, 2));

/**
 * @var string URL dirección absoluta al directorio 'public'.
 */
define('URL', str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']));

/**
 * Permite establecer la zona horaria de la aplicación.
 */
date_default_timezone_set('America/Bogota');

/**
 * ---------------------------------------------------------------------------------------
 * PSR-4 Autoload & Composer
 * ---------------------------------------------------------------------------------------
 * PSR-4 es un estándar de autoloading para PHP, permite cargar cualquier clase de forma
 * automática sin ser necesario el uso de sentencias require|include. Composer (a parte de
 * gestionar las dependencias) cuenta con una implementación de PSR-4.
 * ---------------------------------------------------------------------------------------
 * @see https://www.php-fig.org/psr/psr-4/
 * @see https://getcomposer.org/doc/
 * @todo Configurar en composer.json
 */
require_once ROOT_PATH . '/vendor/autoload.php';

/**
 * ---------------------------------------------------------------------------------------
 * Environment
 * ---------------------------------------------------------------------------------------
 * Una aplicación generalmente tiene varios entornos (desarrollo, producción), cada entorno
 * maneja diferentes credenciales para configurar servicios, por seguridad y practicidad lo
 * ideal es almacenar estas credenciales en variables de entorno.
 * ---------------------------------------------------------------------------------------
 * @see https://www.12factor.net/config
 */
require __DIR__ . '/Core/environment.php';

/**
 * ---------------------------------------------------------------------------------------
 * APP DEBUG
 * ---------------------------------------------------------------------------------------
 * En modo DEBUG se capturan todos los errores-excepciones que no estén contemplados en un
 * bloque try-Catch, luego se indica de forma amigable el stack e incluso el  código con
 * el error, perfecto para desarrollo, aunque es necesario des habilitarlo en producción.
 * ---------------------------------------------------------------------------------------
 * @var bool APP_DEBUG
 */
define('APP_DEBUG', filter_var(env('APP_DEBUG'), FILTER_VALIDATE_BOOL));
ini_set('display_errors', APP_DEBUG ? 'on' : 'off');

/**
 * ---------------------------------------------------------------------------------------
 * PSR-11 Dependency Container
 * ---------------------------------------------------------------------------------------
 * Uno de los principios de SOLID es la inversion de control, en pocas palabras se refiere
 * a depender de abstracciones, no de clases concretas. Para utilizar una abstracción es
 * necesario inyectar la clase concreta, esto se logra con un contenedor de dependencias.
 * ---------------------------------------------------------------------------------------
 * @see https://www.php-fig.org/psr/psr-11/
 * @var League\Container\Container $container
 */
$container = require __DIR__ . '/Core/container.php';

/**
 * ---------------------------------------------------------------------------------------
 * Application - PSR-15 Dispatcher
 * ---------------------------------------------------------------------------------------
 * Esta clase encapsula el comportamiento del Dispatcher PSR-15 para la pila (queue) de
 * Middleware, también permite registrar la configuración del container mediante
 * ServiceProviders.
 * ---------------------------------------------------------------------------------------
 * @see https://www.php-fig.org/psr/psr-15/
 */
$app = new App\Application($container);

/**
 * ---------------------------------------------------------------------------------------
 * Application Boot - Service Providers
 * ---------------------------------------------------------------------------------------
 * Permite cargar los ServiceProviders registrados en la aplicación, si no se ejecuta este
 * método, la mayoría de servicios no estarán disponibles y cualquier dependencia no
 * escalar que deba resolver el Container, se debe registrar de forma manual.
 * ---------------------------------------------------------------------------------------
 */
$app->bootServices();

/**
 * ---------------------------------------------------------------------------------------
 * HTTP Request PSR-7
 * ---------------------------------------------------------------------------------------
 * PSR-7 es un estándar de comunicación HTTP, el Request es un wrapper de las variables
 * globales, se utiliza principalmente para obtener datos del cliente mediante una
 * interfaz orientada a objetos.
 * ---------------------------------------------------------------------------------------
 * @var Psr\Http\Message\ServerRequestInterface $request
 */
$request = $container->get(Psr\Http\Message\ServerRequestInterface::class);

$app->run($request);
