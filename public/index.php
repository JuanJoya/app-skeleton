<?php

/**
 * @author Juan David Joya Ortiz <ing.juanjoya@outlook.com>
 * @license MIT
 */

declare(strict_types=1);

/**
 * ---------------------------------------------------------------------------------------
 * Front-end Controller
 * ---------------------------------------------------------------------------------------
 * Único punto de entrada de la aplicación, aquí se configuran todos los componentes
 * necesarios, único fichero PHP que debe estar en la carpeta publica del proyecto, por
 * seguridad el código del front-end controller se coloca en un archivo externo.
 * ---------------------------------------------------------------------------------------
 * @see https://en.wikipedia.org/wiki/Front_controller
 */
require dirname(__DIR__) . '/app/Src/bootstrap.php';
