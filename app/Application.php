<?php

namespace CustomMVC;
use CustomMVC\Core\Request;

class Application {
    /**
     * @var string con la URL sanitizada
     */
    private $url;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = cleanUrl($url);
    }

    /**
     * Crea una instancia de Request para analizar la URL y ejecutar la petición
     * @return void
     */
    public function run()
    {
        $Request = new Request($this->url);
        $Request->execute();
    }
}
