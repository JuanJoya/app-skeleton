<?php

namespace CustomMVC\User;
use CustomMVC\Core\BaseView;

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
     * @param array $data instancias de User para generar la grilla
     * @return string
     */
    protected function renderDataGrid($html, array $data = [])
    {
        $grid='';
        if($data) {
            foreach ($data as $user) {
                $grid .= "
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
     * implementación del método abstracto de BaseView, construye y retorna el
     * contenido html de la vista
     * @return string $html
     */
    public function render($resource)
    {
        $subtitle = array_shift($this->context);
        $html = parent::render($resource);
        $html = str_replace('{subtitle}', $subtitle[$this->template], $html);
  
        if($this->template == 'listar') {
            $html = $this->renderDataGrid($html, array_shift($this->params));
        }

        $html = $this->renderDynamicData($html, $this->params);

        return $html;
    }

}
