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
 * ROOT = ruta del directorio en el servidor, apunta a la carpeta public
 * URL = ruta 'URI' de la aplicación utilizada para generar 'routes', evita
 * el uso de VirtualHost 
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath( __DIR__ ).DS);
define('URL', "http://localhost/Proyectos/custom-mvc/public/");

$app = new Application( $_GET['url'] );
$app->run();
