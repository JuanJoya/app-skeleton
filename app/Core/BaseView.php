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
     * @param string $template
     * @param array $params data y/o mensaje
     */
    public function __construct($template='', array $params = [])
    {
        $this->template = $template;
        $this->params = $params;
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
     * @param  boolean $print indica si se imprime o retorna el contenido html,
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
        } else {
            return $html;
        }
    }

    /**
     * @param  string $html contenido html
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
        foreach ($data as $key => $value) {
            $html = str_replace('{'.$key.'}', $value, $html);
        }
        return $html;
    }

    /**
     * @param string $resource nombre de la carpeta que contiene las vistas html
     * @param string $template nombre del layout y/o content de la vista html
     * @return string $template string con el contenido literal html
     * @throws \Exception
     */
    protected function getTemplate($resource, $template='layout')
    {
        $resource = lcfirst($resource);
        $file = ROOT.'html'.DS.$resource.DS.$template.'.html';

        if (!file_exists($file)) {
            throw new \Exception("Error, resource not found");
        }

        $template = file_get_contents($file);
        return $template;
    }
}
