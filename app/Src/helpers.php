<?php

/**
 * Este archivo registra de forma global cualquier función que se quiera
 * utilizar a modo de helper en cualquier parte de la aplicación.
 */

declare(strict_types=1);

if (!function_exists('cleanUrl')) {
    /**
     * Limpia la URL de caracteres extraños.
     * @param string $url
     * @return string
     */
    function cleanUrl(string $url): string
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }
}

if (!function_exists('addExtension')) {
    /**
     * Agrega la extensión adecuada al archivo.
     * @param string $fileName
     * @param string $extension
     * @return string
     */
    function addExtension(string $fileName, string $extension): string
    {
        if (preg_match("/.{$extension}$/", $fileName)) {
            return $fileName;
        }
        return $fileName . ".{$extension}";
    }
}

if (!function_exists('normalizeName')) {
    /**
     * Normaliza la notación de punto a slash en el nombre de un template.
     * @param string $template
     * @param string $extension
     * @return string
     */
    function normalizeName(string $template, string $extension = null): string
    {
        return $extension ? str_replace('.', '/', $template) . ".{$extension}" : str_replace('.', '/', $template);
    }
}

if (!function_exists('getUrl')) {
    /**
     * Retorna la ruta completa de un recurso.
     * @param string $url
     * @return string
     */
    function getUrl(string $url): string
    {
        return URL . preg_replace('#^/#', '', $url);
    }
}

if (!function_exists('debug')) {
    /**
     * Interrumpe el flujo de la aplicación para poder depurar el código
     * mediante Psysh (solo funciona con el webserver de PHP).
     * @see https://psysh.org/
     * @return string
     */
    function debug(): string
    {
        define('STDIN', fopen('php://stdin', 'r'));
        return 'extract(\Psy\debug(get_defined_vars(), isset($this) ? $this : @get_called_class()));';
    }
}

if (!function_exists('env')) {
    /**
     * Retorna una variable de entorno registrada en la aplicación.
     * @param string $var environment variable name.
     * @return mixed
     */
    function env(string $var)
    {
        return isset($_ENV[$var]) ? $_ENV[$var] : false;
    }
}

/*
 * La idea de esta función es utilizarla en la definición de helpers para
 * construir objetos internos como Request, Response, no para construir
 * objetos en el modelo de dominio.
 */
if (!function_exists('container')) {
    /**
     * Retorna una instancia del contenedor de dependencias.
     * @param string|null $make nombre [class|interface] para construir una instancia.
     * @return League\Container\Container|object
     */
    function container(string $make = null)
    {
        $c = App\Application::getContainer();
        return $make ? $c->get($make) : $c;
    }
}

if (!function_exists('request')) {
    /**
     * Retorna instancia del objeto Request.
     * @param string $input
     * @return Psr\Http\Message\ServerRequestInterface
     */
    function request(string $input = null)
    {
        return $input
        ? container(Psr\Http\Message\ServerRequestInterface::class)->getAttribute($input)
        : container(Psr\Http\Message\ServerRequestInterface::class);
    }
}

if (!function_exists('response')) {
    /**
     * Retorna instancia del objeto Response.
     * @param string $body
     * @return Psr\Http\Message\ResponseInterface
     */
    function response(string $body): Psr\Http\Message\ResponseInterface
    {
        /**
         * @var Psr\Http\Message\ResponseInterface $response
         */
        $response = container(Psr\Http\Message\ResponseInterface::class);
        $response->getBody()->write($body);
        return $response;
    }
}

if (!function_exists('view')) {
    /**
     * Compila un template y retorna instancia del objeto Response.
     * @param string $template nombre del template.
     * @param array $params parámetros a enviar al template.
     * @param int $status código de status http.
     * @return Psr\Http\Message\ResponseInterface
     */
    function view(string $template, array $params = [], int $status = 200): Psr\Http\Message\ResponseInterface
    {
        return container(App\Src\Response\View::class)->make($template, $params, $status);
    }
}

if (!function_exists('abort')) {
    /**
     * Lanza una excepción del tipo HTTP 404.
     * @return void
     * @throws League\Route\Http\Exception\HttpExceptionInterface
     */
    function abort(): void
    {
        throw new League\Route\Http\Exception\NotFoundException();
    }
}
