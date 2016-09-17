<?php

namespace CustomMVC\Core;

abstract class BaseView implements Response
{
    /**
     * @var string nombre del template a renderizar
     */
	protected $template;
    /**
     * @var array|mixed objetos y/o array que se envía a la vista
     */
    protected $params;
    /**
     * @var array multidimensional con el contexto de la vista
     */
    protected $context;
    /**
     * @var string mensaje opcional con el estado de la app
     */
    protected $message;

    /**
     * @param string $template
     * @param array $params data y/o mensaje
     */
    public function __construct($template='', array $params = [])
    {
        $this->template = $template;
        $this->resolveParams($params);
        $this->setContext();
    }

    /**
     * setea el contenido de la variable $this->context
     */
	abstract protected function setContext();

    /**
     * @param string $resource nombre de la carpeta que contiene las vistas html
     * implementación del método abstracto de BaseView, construye e imprime el
     * contenido html de la vista
     * @param  boolean indica si se imprime o retorna el contenido html, 
     * en caso de sobrescribir el metodo
     * @return string contenido final html
     */
    public function render($resource, $print = true)
    {
        $html = $this->getTemplate($resource);
        $html = str_replace('{content}', $this->getTemplate($resource, $this->template), $html);
        $html = $this->renderContext($html, $this->context);

        if ($print) {
            print $html;
        }
        else {
            return $html;
        }
        
    }

    /**
     * @param  string contenido html 
     * @param  array $context
     * @return string contenido html
     */
    protected function renderContext($html, array $context)
    {
        foreach ($context as $data) {
            $html = $this->renderDynamicData($html, $data);
        }
        return $html;
    }

    /**
     * @param string $html string con la vista html
     * @param array $data data a reemplazar en la vista html
     * @return string $html string final con la data
     */
    protected function renderDynamicData($html, $data)
    {
        foreach ($data as $clave=>$valor) {
            $html = str_replace('{'.$clave.'}', $valor, $html);
        }
        return $html;
    }

    /**
     * @param string $resource nombre de la carpeta que contiene las vistas html
     * @param string $form nombre del layout y/o content de la vista html
     * @return string $template string con el contenido literal html
     */
    protected function getTemplate($resource, $form='layout')
    {
        $resource = lcfirst($resource);
        $file = ROOT.'html'.DS.$resource.DS.$form.'.html';

        if (!file_exists($file)) {
            throw new \Exception("Error, resource not found");
        }

        $template = file_get_contents($file);
        return $template;
    }

    /**
     * @param array $params data y/o mensaje que viene del controller
     */
    private function resolveParams(array $params)
    {
        $this->params = array_shift($params);
        $this->message = array_shift($params);
    }
}
