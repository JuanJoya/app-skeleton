<?php
namespace CustomMVC\Home;
use CustomMVC\Core\BaseView;

class View extends BaseView
{
    /**
     * genera el contenido de la variable $this->context
     * es parte de la data que se renderiza en el template
     */
    public function setContext()
    {
        $data = array(
            'assets_links'=>array(
                    'css1'=>URL.'css/normalize.css',
                    'css2'=>URL.'css/style.css'
            ), 
            'routes' => array(
                    'user'=>URL.'user/'
            )
        );
        $this->context = $data;
    }

    /**
     * @param string $resource nombre de la carpeta que contiene las vistas html
     * implementación del método abstracto de BaseView, construye e imprime el
     * contenido html de la vista
     */
    public function render($resource)
    {
        $html = $this->getTemplate($resource);
        $html = str_replace('{formulario}', $this->getTemplate($resource, $this->template), $html);
        $html = $this->renderDynamicData($html, $this->context['assets_links']);
        $html = $this->renderDynamicData($html, $this->context['routes']);
        print $html;
    }

}
