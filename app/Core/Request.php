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
     * se divide la URL en un array para analizar cada segmento
     */
    public function __construct($url)
    {
        $this->url = $url;
        $segments = explode('/', $this->getUrl());
        $this->resolveResource($segments);
        $this->resolveEvent($segments);
        $this->resolveParams($segments);
    }

    /**
     * @param array $segments
     * resuelve el primer segmento de la URL para identificar el recurso
     */
    public function resolveResource(&$segments)
    {
        $this->resource = ucfirst(array_shift($segments));
        if (empty($this->resource)){
            $this->resource = $this->defaultResource;
        }
    }

    /**
     * @param array $segments
     * resuelve el segundo segmento de la URL para identificar el evento
     */
    public function resolveEvent(&$segments)
    {
        $this->event = array_shift($segments);
        if (empty($this->event))
        {
            $this->event = $this->defaultEvent;
        }
    }

    /**
     * @param array $segments
     * resuelve el segmento final de la URL para identificar los parámetros
     * opcionales de la URL
     */
    public function resolveParams(&$segments)
    {
        $this->params = $segments;
    }

    /**
     * @return string $path ruta del directorio del recurso en el servidor
     */
    public function getResourcePath()
    {
    	$path = dirname(__DIR__).'\\'.$this->resource;
    	return $path;
    }

    /**
     * @return string $name_space construye el namespace para poder instanciar
     * el controller del recurso
     */
    public function getResourceNameSpace()
    {
        $name_space = "CustomMVC\\$this->resource\\Controller";
        return $name_space;
    }

    /**
     * @return string devuelve la URL del request
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Identifica si el recurso existe en el servidor, luego si el evento del
     * recurso existe, si no existe se genera un error 404
     * se instancia el controller adecuado, se llama al evento con los parámetros
     * adicionales
     * el evento del controller retorna una instancia de View
     * Se renderiza la vista
     */
    public function execute()
    {
        $event = $this->event;
        $path = $this->getResourcePath();
        $controller = $this->getResourceNameSpace();

        if (!file_exists($path) || !method_exists($controller, $event))
        {
            $event = 'error';
            $this->resource = $this->defaultResource;
            $controller = $this->getResourceNameSpace();
        }
        
        $response = new $controller;
        $response = $response->$event($this->params);
        $response->render($this->resource);
    }

}