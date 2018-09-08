<?php declare(strict_types = 1);
/**
 * @file
 * Este archivo registra de forma global cualquier function que se quiera
 * utilizar a modo de helper en cualquier parte de la aplicación.
 */

if (!function_exists('cleanUrl')) {
    /**
     * limpia la URL de caracteres extraños.
     * @param string $url
     * @return string
     */
    function cleanUrl(string $url): string
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }
}

if (!function_exists('resolveExtension')) {
    /**
     * Agrega la extension adecuada al archivo.
     * @param string $fileName
     * @param string $extension
     * @return string
     */
    function resolveExtension(string $fileName, string $extension): string
    {
        if (preg_match("/.{$extension}$/", $fileName)) {
            return $fileName;
        }
        return $fileName . ".{$extension}";
    }
}

if (!function_exists('getUrl')) {
    /**
     * Devuelve la ruta completa de un recurso.
     * @param string $url
     * @return string
     */
    function getUrl(string $url): string
    {
        $url = preg_replace('#^/#', '', $url);
        return URL . $url;
    }
}

/*
 * La idea de esta función es utilizarla en la definición de helpers para
 * construir objetos internos como Request, Response, no para construir
 * objetos en el modelo de dominio.
 */
if (!function_exists('container')) {
    /**
     * Devuelve una instancia del contenedor de dependencias.
     * @param string|null $make
     * @return \Auryn\Injector|mixed
     * @throws \Auryn\InjectionException
     */
    function container(string $make = null)
    {
        global $injector;
        if ($injector instanceof \Auryn\Injector) {
            if (is_null($make)) {
                return $injector;
            }
            return $injector->make($make);
        }
        throw new BadFunctionCallException("There is not yet a container configured");
    }
}

if (!function_exists('request')) {
    /**
     * Devuelve instancia de Http\HttpRequest.
     * @return \Http\HttpRequest
     * @throws \Auryn\InjectionException
     */
    function request(): \Http\HttpRequest
    {
        return container('Http\HttpRequest');
    }
}

if (!function_exists('response')) {
    /**
     * Devuelve instancia de Http\HttpResponse.
     * @return \Http\HttpResponse
     * @throws \Auryn\InjectionException
     */
    function response(): \Http\HttpResponse
    {
        return container('Http\HttpResponse');
    }
}

if (!function_exists('render')) {
    /**
     * Devuelve una cadena con respuesta Html.
     * @param string $template
     * @param array $params
     * @return string
     */
    function render(string $template, array $params = []): string
    {
        $path =  dirname(__DIR__) . "/templates/{$template}.php";
        if (file_exists($path)) {
            extract($params);
            ob_start();
            require $path;
            return ob_get_clean();
        } else {
            throw new InvalidArgumentException("the {$template} file does not exist");
        }
    }
}

if (!function_exists('abort')) {
    /**
     * Devuelve una cadena con respuesta Html para errores
     * Http 404, 405, 500.
     * @param string $fileName
     * @return string
     */
    function abort(string $fileName): string
    {
        try {
            return render("errors/{$fileName}");
        } catch (InvalidArgumentException $e) {
            return "Http Error";
        }
    }
}
