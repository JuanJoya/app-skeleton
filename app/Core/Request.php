<?php
namespace CustomMVC\Core;

class Request{

    private $url;
    private $resource;
    private $default_resource = 'Home';
    private $event;
    private $default_event = 'index';
    private $params = array();

    public function __construct($url){
        $this->url = $url;
        $segments = explode('/', $this->get_url());
        $this->resolve_resource($segments);
        $this->resolve_event($segments);
        $this->resolve_params($segments);
    }

    public function resolve_resource(&$segments){
        $this->resource = ucfirst(array_shift($segments));
        if (empty($this->resource)){
            $this->resource = $this->default_resource;
        }
    }

    public function resolve_event(&$segments)
    {
        $this->event = array_shift($segments);
        if (empty($this->event)){
            $this->event = $this->default_event;
        }
    }

    public function resolve_params(&$segments){
        $this->params = $segments;
    }

    public function get_resource_path(){
    	$path = dirname(__DIR__).'\\'.$this->resource;
    	return $path;
    }

    public function get_resource_name_space(){
        $name_space = "CustomMVC\\$this->resource\\Controller";
        return $name_space;
    }

    public function get_url(){
        return $this->url;
    }

    public function execute(){
        $event = $this->event;
        $path = $this->get_resource_path();
        $controller = $this->get_resource_name_space();

        if (!file_exists($path) || !method_exists($controller, $event)){
            $event = 'error';
            $this->resource = $this->default_resource;
            $controller = $this->get_resource_name_space();
        }
        
        $response = new $controller;
        $response = $response->$event($this->params);
        $response->render($this->resource);
    }

}