<?php

namespace CustomMVC\Home;
use CustomMVC\Core\BaseView;

class View extends BaseView
{
    /**
     * @return array
     */
    protected function getContext()
    {
        return array(
            'css1' => URL.'css/normalize.css',
            'css2' => URL.'css/style.css',
            'user' => URL.'user/'
        );
    }
}
