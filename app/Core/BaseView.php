<?php
namespace CustomMVC\Core;

abstract class BaseView
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
     * @param string $resource nombre de la carpeta que contiene las vistas html
     * Imprime el contenido html
     */
	abstract public function render($resource);

    /**
     * genera el contenido de la variable $this->context
     */
	abstract public function setContext();

    /**
     * @param string $html string con la vista html
     * @param array $data data a reemplazar en la vista html
     * @return string $html string final con la data
     */
    public function renderDynamicData($html, $data)
    {
        foreach ($data as $clave=>$valor)
        {
            $html = str_replace('{'.$clave.'}', $valor, $html);
        }
        return $html;
    }

    /**
     * @param string $resource nombre de la carpeta que contiene las vistas html
     * @param string $form nombre del layout y/o content de la vista html
     * @return string $template string con el contenido literal html
     */
    public function getTemplate($resource, $form='template')
    {
        $resource = lcfirst($resource);
        $file = ROOT."html\\$resource\\$form.html";
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
