<?php

namespace CustomMVC\Home;
use CustomMVC\Core\BaseView;

class View extends BaseView
{
    /**
     * setea el contenido de la variable $this->context
     * es parte de la data que se renderiza en el template
     */
    protected function setContext()
    {
        $this->context = [
            'assets_links' => [
                    'css1' => URL.'css/normalize.css',
                    'css2' => URL.'css/style.css'
            ], 
            'routes' => [
                    'user' => URL.'user/'
            ]
        ];
    }
}
