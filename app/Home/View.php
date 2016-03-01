<?php
namespace CustomMVC\Home;
use CustomMVC\Core\BaseView;

class View extends BaseView {

    public function set_context(){
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

    public function render($resource) {
        $html = $this->get_template($resource);
        $html = str_replace('{formulario}', $this->get_template($resource, $this->template), $html);
        $html = $this->render_dinamic_data($html, $this->context['assets_links']);
        $html = $this->render_dinamic_data($html, $this->context['routes']);       
        print $html;
    }

}
