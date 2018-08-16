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
    private $userData = [];

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
     * $user es una instancia de User
     */
    public function get()
    {
        $user = $this->userModel->get($this->userData);
        if(empty($user)) {
            return new View('buscar', ['message' => $this->userModel->status]);
        } else {
            return new View('modificar', [
                'email'    => $user->getEmail(),
                'nombre'   => $user->getFirstName(),
                'apellido' => $user->getLastName(),
                'message'  => $this->userModel->status
            ]);
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
     */
    public function listar()
    {
        return new View('listar', [
            'users'   => $this->userModel->all(),
            'message' => $this->userModel->status
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function api()
    {
        return new JsonResponse(
            $this->userModel->allToArray()
        );
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
        $this->userModel->delete($this->userData['email']);
        return new View('borrar', ['message' => $this->userModel->status]);
    }

    /**
     * @return View edit action del formulario que permite modificar un usuario
     */
    public function edit()
    {
        $this->userModel->update($this->userData);
        return new View('buscar', ['message' => $this->userModel->status]);
    }

    /**
     * @return array|mixed $this->userData
     * sanitiza la data que viene de los formularios por GET y POST
     */
    public function helperUserData() 
    {
        if($_POST) {
            $this->userData = array_map('trim',$_POST);
            $args = [
                'first_name' => FILTER_SANITIZE_STRING,
                'last_name'  => FILTER_SANITIZE_STRING,
                'email'      => FILTER_VALIDATE_EMAIL,
                'password'   => FILTER_UNSAFE_RAW,
            ];  
            $this->userData = filter_var_array($this->userData, $args, false);

        } elseif(isset($_GET['email'])) {
            $this->userData = array_map('trim',$_GET);
            $this->userData = filter_var($this->userData['email'], FILTER_VALIDATE_EMAIL);
        }
    }
}
