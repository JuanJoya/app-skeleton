<?php declare(strict_types = 1);
/**
 * @file
 * @see https://github.com/illuminate/database/tree/v5.5.41
 * ---------------------------------------------------------------------------------------
 * Este script crea el objeto Capsule, este nos permite configurar fácilmente la conexión
 * a la base de datos y preparar el ORM. Hecho esto podemos utilizar el Query Builder, el
 * Schema Builder y los modelos de Eloquent.
 * ---------------------------------------------------------------------------------------
 */

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Permite utilizar el objeto Capsule de forma global mediante métodos estáticos.
$capsule->setAsGlobal();

return $capsule;
