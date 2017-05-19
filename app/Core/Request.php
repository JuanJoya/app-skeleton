<?php

namespace CustomMVC\Core;

class Request
{
    /**
     * @var string con la URL del Request
     */
    private $url;
    /**
     * @var string primer parámetro de la URL con el nombre del recurso
     */
    private $resource;
    /**
     * @var string nombre por defecto del recurso
     */
    private $defaultResource = 'Home';
    /**
     * @var string segundo parámetro de la URL con el nombre del evento
     * (function) a ejecutar en el controller
     */
    private $event;
    /**
     * @var string nombre por defecto del evento
     */
    private $defaultEvent = 'index';
    /**
     * @var array parámetros adicionales de la URL
     */
    private $params = array();

    /**
     * @param string $url string con la URL del Request
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->resolveUrl();
    }

    /**
     * @return string devuelve la URL del request
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * se divide la URL en un array para analizar cada segmento
     */
    protected function resolveUrl()
    {
        $segments = explode('/', $this->getUrl());
        $this->resolveResource($segments);
        $this->resolveEvent($segments);
        $this->resolveParams($segments);
    }

    /**
     * @param array $segments
     * resuelve el primer segmento de la URL para identificar el recurso
     */
    protected function resolveResource(&$segments)
    {
        $this->resource = ucfirst(array_shift($segments));
        if (empty($this->resource)) {
            $this->resource = $this->defaultResource;
        }
    }

    /**
     * @param array $segments
     * resuelve el segundo segmento de la URL para identificar el evento
     */
    protected function resolveEvent(&$segments)
    {
        $this->event = array_shift($segments);
        if (empty($this->event)) {
            $this->event = $this->defaultEvent;
        }
    }

    /**
     * @param array $segments
     * resuelve el segmento final de la URL para identificar los parámetros
     * opcionales de la URL
     */
    protected function resolveParams(&$segments)
    {
        $this->params = $segments;
    }

    /**
     * @return string $path ruta del directorio del recurso en el servidor
     */
    protected function getResourcePath()
    {
    	$path = dirname(__DIR__).DS.$this->resource;
    	return $path;
    }

    /**
     * @return string $namespace construye el namespace para poder instanciar
     * el controller del recurso
     */
    protected function getResourceNamespace()
    {
        $namespace = "CustomMVC\\$this->resource\\Controller";
        return $namespace;
    }

    /**
     * Identifica si el recurso existe en el servidor, luego si el evento del
     * recurso existe, si no existe se genera un error 404
     * se instancia el controller adecuado, se llama al evento con los parámetros
     * adicionales
     * el evento del controller retorna una instancia de View o cualquier clase que 
     * implemente Response, Se renderiza la vista
     */
    public function execute()
    {
        $event = $this->event;
        $path = $this->getResourcePath();
        $controller = $this->getResourceNamespace();

        if (!file_exists($path) || !method_exists($controller, $event)) {
            $event = 'error';
            $this->resource = $this->defaultResource;
            $controller = $this->getResourceNamespace();
        }

        $response = new $controller;
        $response = $response->$event($this->params);

        $this->validateResponse($response);
    }

    /**
     * @param  mixed response que retorna el controlador
     */
    protected function validateResponse($response)
    { 
        if ($response instanceof Response) {
            print $response->render($this->resource);
        }
        else {
            header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Server Error"); 
            exit('Invalid Response');
        }
    }
}
