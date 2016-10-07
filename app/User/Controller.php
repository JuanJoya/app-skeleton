<?php

namespace CustomMVC\User;
use CustomMVC\Core\JsonResponse;

class Controller 
{
    /**
     * @var UserRepository instancia del modelo del usuario
     */
    private $userModel;
    /**
     * @var array|mixed data que proviene de los formularios
     */
    private $userData;

    public function __construct()
    {
        $this->userModel = new UserRepository();
        $this->helperUserData();
    }

    /**
     * @return View template por defecto
     */
    public function index()
    {
        return new View('buscar', ['message' => 'up']);
    }

    /**
     * @return View url = user/buscar
     */
    public function buscar()
    {
        return new View('buscar', ['message' => 'up']);
    }

    /**
     * @return View get action del formulario que permite buscar usuarios
     * $user es una instancia del ValueObject User
     */
    public function get()
    {
        $user = $this->userModel->get($this->userData);
        if(empty($user)) {

            return new View('buscar', ['message' => $this->userModel->status]);
        } else {
            $data['email'] = $user->getEmail();
            $data['nombre'] = $user->getFirstName();
            $data['apellido'] = $user->getLastName();
            $data['message'] = $this->userModel->status;

            return new View('modificar', $data);
        }
    }

    /**
     * @return View url = user/agregar
     */
    public function agregar()
    {
        return new View('agregar', ['message' => 'up']);
    }

    /**
     * @return View set action del formulario que permite crear un usuario
     */
    public function set()
    {  
        $this->userModel->set($this->userData);
        return new View('agregar', ['message' => $this->userModel->status]);
    }

    /**
     * @return View url = user/listar
     * $data['users'] es un array construido con Illuminate\Support\Collection que almacena
     * una colección del ValueObject User
     */
    public function listar()
    {
        $data['users'] = $this->userModel->all()->toArray();
        $data['message'] = $this->userModel->status;

        return new View('listar', $data);
    }

    /**
     * @return JsonResponse
     */
    public function api()
    {
        $users = $this->userModel->all()->toArray();
        /**
         * * @type User $user
         */
        foreach ($users as $user) {
            $list[$user->getId()] = [
                'first_name' => $user->getFirstName(),
                'last_name'  => $user->getLastName(),
                'email'      => $user->getEmail()
            ];
        }
        return new JsonResponse($list);
    }  

    /**
     * @return View url = user/borrar
     */
    public function borrar()
    {
        return new View('borrar', ['message' => 'up']);
    }

    /**
     * @return View delete action del formulario que permite borrar un usuario
     */
    public function delete()
    {
        $this->userModel->delete($this->userData);
        return new View('borrar', ['message' => $this->userModel->status]);
    }

    /**
     * @return View edit action del formulario que permite modificar un usuario
     */
    public function edit()
    {
        $this->userModel->edit($this->userData);
        return new View('buscar', ['message' => $this->userModel->status]);
    }

    /**
     * @return array|mixed $this->userData
     * sanitiza la data que viene de los formularios por GET y POST
     */
    public function helperUserData() 
    {
        $this->userData = [];
        if($_POST) {
            $this->userData = array_map('trim',$_POST);
            $args = [
                'nombre'    => FILTER_SANITIZE_STRING,
                'apellido'  => FILTER_SANITIZE_STRING,
                'email'     => FILTER_VALIDATE_EMAIL,
                'clave'     => FILTER_UNSAFE_RAW,
            ];  
            $this->userData = filter_var_array($this->userData, $args, false);
        } 
        elseif(isset($_GET['email'])) {
            $this->userData = array_map('trim',$_GET);
            $this->userData = filter_var($this->userData['email'], FILTER_VALIDATE_EMAIL);
        }
        return $this->userData;
    }
}
