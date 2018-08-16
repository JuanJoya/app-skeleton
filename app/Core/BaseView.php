<?php

namespace CustomMVC\Core;

abstract class BaseView implements Response
{
    /**
     * @var string nombre del template a renderizar
     */
	protected $template;
    /**
     * @var array data que se envía a la vista
     */
    protected $params = array();

    /**
     * @param string $template
     * @param array $params data y/o mensaje
     */
    public function __construct($template, array $params = [])
    {
        if (empty($template)) {
            throw new \InvalidArgumentException('Empty template');
        }
        $this->template = $template;
        $this->params = $params;
    }

    /**
     * @return array contexto de la vista
     */
	abstract protected function getContext();

    /**
     * @param string $resource nombre de la carpeta que contiene las vistas html
     * implementación del método abstracto de BaseView, construye y retorna el
     * contenido html de la vista
     * @return string $html
     */
    public function render($resource)
    {
        $html = $this->getTemplate($resource);
        $html = str_replace('{content}', $this->getTemplate($resource, $this->template), $html);
        $html = $this->renderDynamicData($html, $this->getContext());

        return $html;
    }

    /**
     * @param string $html string con la vista html
     * @param array $data data a reemplazar en la vista html
     * @return string $html string final con la data
     */
    protected function renderDynamicData($html, array $data = [])
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
     */
    protected function getTemplate($resource, $template = 'layout')
    {
        $resource = lcfirst($resource);
        $file = dirname(dirname(__DIR__)) . '/resources/' . $resource . '/' . $template . '.html';
        if (!file_exists($file)) {
            throw new \RuntimeException("Error, resource not found");
        }
        $template = file_get_contents($file);

        return $template;
    }
}
