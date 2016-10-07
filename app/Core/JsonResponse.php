<?php

namespace CustomMVC\Core;

class JsonResponse implements Response
{
	/**
	 * array de datos a codificar en json
	 * @var array
	 */
	protected $value = [];

	public function __construct(array $value = [])
	{
		$this->value = $value;
	}

	/**
	 * @param  string $resource nombre del recurso
	 */
	public function render($resource)
	{
		header("Content-Description: api:{$resource}"); 
		header('Content-type: application/json');
		echo json_encode($this->value);
	}
}
