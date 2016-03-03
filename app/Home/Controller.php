<?php
namespace CustomMVC\Home;

class Controller
{
	/**
	 * @var array parámetros opcionales de la URL
	 */
    private $urlParams;

	/**
	 * @param array $urlParams
	 * @return View instancia de la vista, al constructor se le
	 * envia el nombre del template
	 */
    public function index(array $urlParams=[])
	{
		$this->urlParams = $urlParams;
    	return new View('home');
    }

	/**
	 * evento en caso de error en la URL
	 * @return View
	 */
 	public function error()
	{
 		return new View('404');
 	}
}

