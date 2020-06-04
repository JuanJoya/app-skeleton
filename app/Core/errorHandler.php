<?php

/**
 * @deprecated
 * @internal
 * @see https://github.com/filp/whoops
 * Este script setea el ErrorHandler de la aplicación.
 */

declare(strict_types=1);

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

$errorHandler = new Run();

/*
 * Se verifica con la variable de entorno APP_DEBUG si la aplicación esta en
 * modo (true)Desarrollo o (false|null)Producción.
 */

if (filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN)) {
    /*
     * En desarrollo: el objeto PrettyPageHandler es el manejador de errores,
     * la salida de error se muestra en el navegador con todos los detalles.
     */
    $errorHandler->pushHandler(new PrettyPageHandler());
} else {
    /*
     * En producción: no nos interesa que el usuario tenga información del error
     * lo ideal seria detener la aplicación por completo. De hecho en este punto
     * se lanza un error Http status 500.
     */
    $errorHandler->pushHandler(function ($e) {
        echo abort('500');
    });
}

return $errorHandler;
