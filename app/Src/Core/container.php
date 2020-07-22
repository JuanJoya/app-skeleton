<?php

/**
 * ---------------------------------------------------------------------------------------
 * La inyección de dependencias es una técnica en la cual una clase recibe las dependencias
 * en su constructor, estas dependencias se resuelven mediante un Container, lógicamente
 * es necesario almacenar las dependencias en el Container antes de inyectarlas.
 * ---------------------------------------------------------------------------------------
 * @see https://phptherightway.com/#dependency_injection
 * @see https://container.thephpleague.com/3.x/
 */

declare(strict_types=1);

use League\Container\{Container, ReflectionContainer};

$container = new Container();

/**
 * ---------------------------------------------------------------------------------------
 * Se pueden resolver automáticamente las dependencias de forma recursiva, analizando los
 * argumentos del constructor, utilizando el API Reflection. No es necesario que estén
 * almacenadas en el Container, esto solo funciona si los argumentos no son escalares.
 * ---------------------------------------------------------------------------------------
 * @see https://container.thephpleague.com/3.x/auto-wiring/
 */
$container->delegate(
    new ReflectionContainer()
);

return $container;
