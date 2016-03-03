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
        $this->url = $this->cleanUrl($url);
    }

    /**
     * Crea una instancia de Request para analizar la URL y ejecutar la petici�n
     */
    public function run()
    {
        $Request = new Request($this->url);
        $Request->execute();
    }

    /**
     * @param string $url
     * @return string
     * limpia la URL de caracteres extra�os
     */
    public function cleanUrl($url)
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return $url;
    }
}
