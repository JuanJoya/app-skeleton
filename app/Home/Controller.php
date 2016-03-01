<?php
namespace CustomMVC\Home;

class Controller {

    private $params;

    public function index($params=[]){
    	return new View('home');
    }
 	
 	public function error(){
 		return new View('404');
 	}
}

