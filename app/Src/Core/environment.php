<?php

/**
 * ---------------------------------------------------------------------------------------
 * PHP DotEnv
 * ---------------------------------------------------------------------------------------
 * En un entorno de desarrollo, se cargan las variables de entorno desde un fichero .env
 * para que estén disponibles de forma global, esto se repite en cada request, en entorno
 * de producción se recomienda utilizar variables reales para optimizar el performance.
 * ---------------------------------------------------------------------------------------
 * @see https://github.com/vlucas/phpdotenv
 */

declare(strict_types=1);

use Dotenv\Dotenv;

/**
 * Si la variable no existe, se utiliza el parser DotEnv para cargarlas desde el .env
 */
if (!env('APP_ENV')) {
    if (file_exists(ROOT_PATH . '/.env')) {
        $dotEnv = Dotenv::createImmutable(ROOT_PATH);
        $dotEnv->load();
        $dotEnv->required(['APP_DEBUG'])->isBoolean();
        $dotEnv->required(['APP_KEY']);
    } else {
        throw new RuntimeException('.env file is missing');
    }
}
