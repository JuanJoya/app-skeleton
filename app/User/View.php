<?php

namespace CustomMVC\User;
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
            'home'    => URL,
            'agregar' => URL.'user/agregar',
            'buscar'  => URL.'user/buscar',
            'borrar'  => URL.'user/borrar',
            'listar'  => URL.'user/listar',
            'api'     => URL.'user/api'
        );
    }

    /**
     * @param string $html
     * @return string $html
     */
    private function renderSubtitle($html)
    {
        $subtitle = [
            'agregar'   => 'Crear un nuevo usuario',
            'buscar'    => 'Buscar usuario',
            'borrar'    => 'Eliminar un usuario',
            'modificar' => 'Modificar usuario',
            'listar'    => 'Listar Usuarios'
        ];

        return str_replace('{subtitle}', $subtitle[$this->template], $html);
    }

    /**
     * @param string $html contenido literal del content
     * @param array $data instancias de User para generar la grilla
     * @return string
     */
    private function renderDataGrid($html, $data)
    {
        $grid = "";
        if(is_array($data)) {
            foreach ($data as $user) {
                $grid .= "
                    <tr>
                        <td>{$user->getFirstName()}</td>
                        <td>{$user->getLastName()}</td>
                        <td>{$user->getEmail()}</td>
                    </tr>"; 
            }            
        }
        return str_replace('{data_grid}', $grid, $html);
    }

    /**
     * @param string $resource nombre de la carpeta que contiene las vistas html
     * implementación del método abstracto de BaseView, construye y retorna el
     * contenido html de la vista
     * @return string $html
     */
    public function render($resource)
    {
        $html = parent::render($resource);
        $html = $this->renderSubtitle($html);
        if($this->template == 'listar') {
            $html = $this->renderDataGrid($html, array_shift($this->params));
        }
        $html = $this->renderDynamicData($html, $this->params);

        return $html;
    }
}
