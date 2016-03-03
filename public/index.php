<?php
use CustomMVC\Application;

/**
 * llama el autoload de composer bajo el estándar psr-4
 */
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Permite habilitar o deshabilitar la notificación de errores
 */
ini_set('display_errors', 'off');
error_reporting(E_ALL);

/**
 * constantes utilizadas en la app
 * ROOT = ruta del directorio en el servidor, apunta a la carpeta public
 * URL = ruta http de la aplicación utilizada para generar 'routes'
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath( __DIR__ ).DS);
define('URL', "http://localhost/proyectos/custom-mvc/public/");

$app = new Application( $_GET['url'] );
$app->run();
