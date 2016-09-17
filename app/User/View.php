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
    protected function setContext()
    {
        $this->context = [
            'subtitle' => [
                    'agregar'   => 'Crear un nuevo usuario',
                    'buscar'    => 'Buscar usuario',
                    'borrar'    => 'Eliminar un usuario',
                    'modificar' => 'Modificar usuario',
                    'listar'    => 'Listar Usuarios',
            ],
            'form_actions' => [
                    'SET'    => 'set',
                    'GET'    => 'get',
                    'DELETE' => 'delete',
                    'EDIT'   => 'edit'
            ],
            'assets_links' => [
                    'css1' => URL.'css/normalize.css',
                    'css2' => URL.'css/style.css'
            ],
            'routes' => [
                    'home'    => URL,
                    'agregar' => URL.'user/agregar',
                    'buscar'  => URL.'user/buscar',
                    'borrar'  => URL.'user/borrar',
                    'listar'  => URL.'user/listar',
                    'api'     => URL.'user/api'
            ]  
        ];
    }

    /**
     * @param string $html contenido literal del content
     * @param Collection|null $data instancias de User para generar la grilla
     * @return string
     */
    protected function renderDataGrid($html, Collection $data = null)
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
    public function render($resource, $print = true)
    {
        $subtitle = array_shift($this->context);
        $html = parent::render($resource, false);

        $html = str_replace('{subtitle}', $subtitle[$this->template], $html);
        $html = str_replace('{message}', $this->message, $html);
  
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
