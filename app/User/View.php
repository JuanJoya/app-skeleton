<?php
namespace CustomMVC\User;
use CustomMVC\Core\BaseView;

class View extends BaseView {

    public function set_context(){
        $data = array(
            'subtitle'=>array(
                            'agregar'=>'Crear un nuevo usuario',
                            'buscar'=>'Buscar usuario',
                            'borrar'=>'Eliminar un usuario',
                            'modificar'=>'Modificar usuario',
                            'listar'=>'Listar Usuarios',
                            '404'=>''
                             ),
            'form_actions'=>array(
                            'SET'=>'set',
                            'GET'=>'get',
                            'DELETE'=>'delete',
                            'EDIT'=>'edit'
            ),
            'assets_links'=>array(
                    'css1'=>URL.'css/normalize.css',
                    'css2'=>URL.'css/style.css'
            ),
            'routes' => array(
                    'home'    =>URL,
                    'agregar' =>URL.'user/agregar',
                    'buscar'  =>URL.'user/buscar',
                    'borrar'  =>URL.'user/borrar',
                    'listar'  =>URL.'user/listar'
            )  
        );

        $this->context = $data;
    }

    public function render_data_grid($html, $data=array()){
        $aux='';
        foreach ($data as $value) {
            $aux.="
                <tr>
                    <td>".$value['nombre']."</td>
                    <td>".$value['apellido']."</td>
                    <td>".$value['email']."</td>
                </tr>"; 
        }
        $html = str_replace('{data_grid}', $aux, $html);
        return $html;
    }

    public function render($resource) {

        $html = $this->get_template($resource);
        $html = str_replace('{subtitulo}', $this->context['subtitle'][$this->template], $html);
        $html = str_replace('{formulario}', $this->get_template($resource, $this->template), $html);
        $html = $this->render_dinamic_data($html, $this->context['form_actions']);
        $html = $this->render_dinamic_data($html, $this->context['assets_links']);
        $html = $this->render_dinamic_data($html, $this->context['routes']); 

        
        if($this->template == 'listar'){
            $mensaje = array_pop($this->data);
            $html = $this->render_data_grid($html, $this->data);
            $this->data = ['mensaje' => $mensaje];
        }
        else{ 
            $html = $this->render_dinamic_data($html, $this->data);
        }

        // render {mensaje}
        if(array_key_exists('mensaje', $this->data)) {
            $mensaje = $this->data['mensaje'];
        } else {
            $mensaje = 'Datos del usuario';
        }

        $html = str_replace('{mensaje}', $mensaje, $html);

        print $html;
    }

}
