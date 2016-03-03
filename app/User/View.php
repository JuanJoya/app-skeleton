<?php
namespace CustomMVC\User;
use CustomMVC\Core\BaseView;
use Illuminate\Support\Collection;

class View extends BaseView
{
    /**
     * genera el contenido de la variable $this->context
     * es parte de la data que se renderiza en el template
     */
    public function setContext()
    {
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

    /**
     * @param string $html contenido literal del content
     * @param Collection|null $data instancias de User para generar la grilla
     * @return string
     */
    public function renderDataGrid($html, Collection $data = null)
    {
        $grid='';
        if($data)
        {
            foreach ($data as $user) {
                $grid.="
                    <tr>
                        <td>".$user->getFirstName()."</td>
                        <td>".$user->getLastName()."</td>
                        <td>".$user->getEmail()."</td>
                    </tr>"; 
            }            
        }
        $html = str_replace('{data_grid}', $grid, $html);
        return $html;
    }

    /**
     * @param string $resource nombre de la carpeta que contiene las vistas html
     * implementación del método abstracto de BaseView, construye e imprime el
     * contenido html de la vista
     */
    public function render($resource)
    {
        $html = $this->getTemplate($resource);
        $html = str_replace('{subtitulo}', $this->context['subtitle'][$this->template], $html);
        $html = str_replace('{formulario}', $this->getTemplate($resource, $this->template), $html);
        $html = $this->renderDynamicData($html, $this->context['form_actions']);
        $html = $this->renderDynamicData($html, $this->context['assets_links']);
        $html = $this->renderDynamicData($html, $this->context['routes']);
        $html = str_replace('{mensaje}', $this->message, $html);
  
        if($this->template == 'listar')
        {
            $html = $this->renderDataGrid($html, $this->params);
        }
        elseif($this->params)
        { 
            $html = $this->renderDynamicData($html, $this->params);
        }

        print $html;
    }

}
