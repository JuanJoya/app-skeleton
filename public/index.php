<?php
use CustomMVC\Application;
require_once __DIR__ . '/../vendor/autoload.php';

//Config
ini_set('display_errors', 'on');
error_reporting(E_ALL);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath( __DIR__ ).DS);
define('URL', "http://localhost/proyectos/CustomMVC/public/");

$app = new Application( $_GET['url'] );
$app->run();
