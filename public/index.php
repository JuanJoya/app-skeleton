<?php

/**
 * Este proyecto es una simple prueba de conceptos, implementa las siguientes
 * tecnologías: inyección de dependencias, URL Rewrite, URI Router, ORM, DotEnv,
 * HTTP Request & Response, Error Handler, Template Engine, Data Validation.
 * La finalidad es entender el funcionamiento interno de un micro framework.
 *
 * @todo La configuración necesaria se aplica en el archivo .env en la raíz del proyecto.
 * @author Juan David Joya <ing.juanjoya@outlook.com>
 * @license MIT
 */

declare(strict_types=1);

/**
 * ---------------------------------------------------------------------------------------
 * Front-end Controller
 * ---------------------------------------------------------------------------------------
 * Este archivo funciona como único punto de entrada para nuestra aplicación, es un simple
 * script donde se inicializan todos los componentes necesarios, es el único script PHP
 * que debe estar en la carpeta publica del proyecto, un fallo en el servidor web podría
 * exponer toda la lógica de la aplicación, por esta razón colocamos el código del
 * front-end controller en un archivo externo.
 */
require dirname(__DIR__) . '/app/bootstrap.php';
