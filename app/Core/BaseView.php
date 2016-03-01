<?php
namespace CustomMVC\Core;

abstract class BaseView {

	protected $template;
	protected $default_template = '';
    protected $data;
    protected $context;

    public function __construct($template='', $data=[]){
        $this->template = $template;
        $this->data = $data;

        if(empty($this->template)){
        	$this->template = $this->default_template;
        }

        $this->set_context();
    }

	abstract public function render($resource);
	abstract public function set_context();

    public function render_dinamic_data($html, $data) {
        foreach ($data as $clave=>$valor) {
            $html = str_replace('{'.$clave.'}', $valor, $html);
        }
        return $html;
    }

    public function get_template($resource, $form='template') {
        $resource = lcfirst($resource);
        $file = ROOT."html\\$resource\\$form.html";
        $template = file_get_contents($file);
        return $template;
    }
}
