<?php
namespace CustomMVC;
use CustomMVC\User\Controller;
use CustomMVC\Core\Request;

class Application {

    private $url;

    public function __construct($url){
        $this->url = $this->clean_url($url);
    }

    public function run(){
        $Request = new Request($this->url);
        $Request->execute();
    }

    public function clean_url($url){
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return $url;
    }
}
