<?php

use CustomMVC\Application;

/**
 * llama el autoload de composer bajo el estándar psr-4
 */
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Permite habilitar o deshabilitar la notificación de errores
 */
ini_set('display_errors', 'on');
error_reporting(E_ALL);

/**
 * constantes utilizadas en la app
 * URL = String con los directorios necesarios para llegar a la carpeta 'public' en apache.
 */
define('URL', str_replace(basename( $_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']) );

$app = new Application( $_GET['url'] );
$app->run();
