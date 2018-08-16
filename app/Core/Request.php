<?php

namespace CustomMVC\Core;

class Request
{
    /**
     * @var string URL del Request.
     */
    private $url;

    /**
     * @var string primer parámetro de la URL con el nombre del recurso.
     */
    private $resource;

    /**
     * @var string nombre por defecto del recurso.
     */
    private $defaultResource = 'Home';

    /**
     * @var string segundo parámetro de la URL con el nombre del action
     * (function) a ejecutar en el controller.
     */
    private $action;

    /**
     * @var string nombre por defecto del action.
     */
    private $defaultAction = 'index';

    /**
     * @var string action que retorna error 404.
     */
    private $invalidAction = 'error';

    /**
     * @var array parámetros adicionales de la URL.
     */
    private $params = array();

    /**
     * @param string $url URL del Request.
     */
    public function __construct($url = '')
    {
        $this->url = $url;
        $this->resolveUrl();
    }

    /**
     * Se divide la URL en un array para analizar cada segmento.
     * @return void
     */
    private function resolveUrl()
    {
        $segments = explode('/', $this->getUrl());
        $this->resolveResource($segments);
        $this->resolveAction($segments);
        $this->resolveParams($segments);
    }

    /**
     * @param array $segments
     * Resuelve el primer segmento de la URL para identificar el recurso.
     * @return void
     */
    private function resolveResource(&$segments)
    {
        $this->resource = ucfirst(array_shift($segments));
        if (empty($this->resource)) {
            $this->resource = $this->defaultResource;
        }
    }

    /**
     * @param array $segments
     * Resuelve el segundo segmento de la URL para identificar el action.
     * @return void
     */
    private function resolveAction(&$segments)
    {
        $this->action = array_shift($segments);
        if (empty($this->action)) {
            $this->action = $this->defaultAction;
        }
    }

    /**
     * @param array $segments
     * Resuelve el segmento final de la URL para identificar los parámetros
     * opcionales de la URL.
     * @return void
     */
    private function resolveParams(&$segments)
    {
        $this->params = $segments;
    }

    /**
     * @return string $path ruta del directorio del recurso en el servidor.
     */
    private function getResourcePath()
    {
        $path = dirname(__DIR__) . '/' . $this->resource;
    	return $path;
    }

    /**
     * @return string $namespace construye el namespace para poder instanciar
     * el controller del recurso.
     */
    private function getResourceNamespace()
    {
        $namespace = "CustomMVC\\{$this->resource}\\Controller";
        return $namespace;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Se instancia el controller adecuado, se llama el action con los parámetros opcionales, 
     * se valida lo que retorne el action del Controller.
     * @return void
     */
    public function execute()
    {
        $this->validateRequest(
            $this->getResourcePath(),
            $this->getResourceNamespace(),
            $this->getAction()
        );

        $resource   = $this->getResourceNamespace();
        $controller = new $resource; 
        $response   = call_user_func_array([$controller, $this->getAction()], $this->getParams());

        $this->validateResponse($response);
    }

    /**
     * Identifica si el recurso existe en el servidor y si el action del recurso esta definido, 
     * si no existe se setea un action que retorna una vista 404.
     * @param string $path
     * @param string $className
     * @param string $method
     * @return void
     */
    private function validateRequest($path, $className, $method)
    {
        if (!file_exists($path) || !method_exists($className, $method)) {
            $this->action   = $this->invalidAction;
            $this->resource = $this->defaultResource;
        }
    }

    /**
     * @param  mixed response que retorna el controlador.
     * @return void
     */
    private function validateResponse($response)
    { 
        if ($response instanceof Response) {
            echo $response->render($this->resource);
        }
        else {
            header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Server Error"); 
            exit('Invalid Response');
        }
    }
}
