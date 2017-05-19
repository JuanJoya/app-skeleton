<?php

namespace CustomMVC\Home;

class Controller
{

	/**
	 * @return View instancia de la vista, al constructor se le
	 * envia el nombre del template
	 */
    public function index()
	{
    	return new View('home');
    }

	/**
	 * evento en caso de error en la URL
	 * @return View
	 */
 	public function error()
	{
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
 		return new View('404');
 	}
}
