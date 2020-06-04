<?php

/**
 * @deprecated
 */

declare(strict_types=1);

/**
 * @internal
 * @see https://github.com/rdlowrey/auryn
 * ---------------------------------------------------------------------------------------
 * Este script crea el IoC Container|Injector para la inyección de dependencias, el
 * Injector utiliza un api de PHP conocida como Reflection para realizar ingeniería
 * inversa y asi determinar las dependencias en la construcción de un objeto. Se
 * recomienda no pasar el Injector a las clases de aplicación ya que se incurre en
 * un anti patron conocido como Service Locator.
 * ---------------------------------------------------------------------------------------
 * Aquí podemos configurar la forma en que el Injector a de resolver las
 * dependencias.
 */

use Auryn\Injector;
use App\Core\Template\{TemplateEngine, Twig\Twig};
use Twig\Loader\{FilesystemLoader, LoaderInterface};

$injector = new Injector();

/*
 * ---------------------------------------------------------------------------------------
 * Injector Aliases
 * ---------------------------------------------------------------------------------------
 * Los aliases nos permiten registrar la implementación concreta de una interface o de una
 * clase abstracta, gracias a esto solo es necesario tipear la interface|abstractClass en
 * el constructor y no una implementación.
 */
$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->alias(TemplateEngine::class, Twig::class);

/*
 * ---------------------------------------------------------------------------------------
 * Injector Sharing
 * ---------------------------------------------------------------------------------------
 * En algunos casos es necesario tener una sola instancia de una clase en nuestra app,
 * comúnmente esto se logra mediante propiedades estáticas pero puede presentar problemas
 * a la hora de hacer pruebas unitarias. El método share le indica al Injector que
 * devuelva siempre la misma instancia de un objeto sin caer en el anti patron singleton.
 */
$injector->share('Http\HttpRequest');
$injector->share('Http\HttpResponse');

/*
 * ---------------------------------------------------------------------------------------
 * Injector Define
 * ---------------------------------------------------------------------------------------
 * Si nuestras clases requieren de parámetros escalares o que no sean objetos, el Injector
 * no puede resolver las dependencias por si solo, en estos casos se utiliza el método
 * Define para indicarle al Injector la forma en que debe construir el objeto.
 */
$injector->define('Http\HttpRequest', [
    ':get' => $_GET,
    ':post' => $_POST,
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);

/*
 * ---------------------------------------------------------------------------------------
 * Injector Delegate
 * ---------------------------------------------------------------------------------------
 * El método Delegate nos permite delegar la creación del objeto a una clase tipo Factory
 * (con __invoke()) o a un simple Callback, esto es importante si el objeto necesita una
 * preparación especifica.
 */
$injector->delegate(LoaderInterface::class, function () {
    return new FilesystemLoader(ROOT_PATH . '/resources/views');
});

return $injector;
