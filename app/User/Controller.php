<?php
namespace CustomMVC\User;

class Controller {

    private $params;
    private $user;
    private $user_data;

    public function __construct(){
        $this->user = new User();
        $this->helper_user_data();
    }

    public function index($params=[]){
        return new View('buscar');
    }

    public function buscar(){
        return new View('buscar');
    }

    public function get(){
        $this->user->get($this->user_data);
        $data = array(
            'nombre'=>$this->user->nombre,
            'apellido'=>$this->user->apellido,
            'email'=>$this->user->email,
            'mensaje'=>$this->user->status
        );
        if(empty($data['email'])){
            return new View('buscar', $data);
        }else{
            return new View('modificar', $data);
        }
    }

    public function agregar(){
        return new View('agregar');
    }

    public function set(){  
        $this->user->set($this->user_data);
        $data = array('mensaje'=>$this->user->status);
        return new View('agregar', $data);
    }

    public function listar(){
        $data = $this->user->get_all();
        $data['mensaje'] = $this->user->status;
        return new View('listar', $data);
    }

    public function borrar(){
        return new View('borrar');
    }

    public function delete(){
        $this->user->delete($this->user_data);
        $data = array('mensaje'=>$this->user->status);
        return new View('borrar',$data);
    }

    public function edit(){
        $this->user->edit($this->user_data);
        $data = array('mensaje'=>$this->user->status);
        return new View('buscar',$data);
    }

    public function helper_user_data() {
        $this->user_data = array();
        if($_POST) {
            $this->user_data = array_map('trim',$_POST);
            $args = array(
                'nombre'   => FILTER_SANITIZE_STRING,
                'apellido'     => FILTER_SANITIZE_STRING,
                'email'   => FILTER_VALIDATE_EMAIL,
                'clave'     => FILTER_UNSAFE_RAW,
            );  
            $this->user_data = filter_var_array($this->user_data, $args, false);
        } else if(isset($_GET['email'])) {
            $this->user_data = array_map('trim',$_GET);
            $this->user_data = filter_var($this->user_data['email'], FILTER_VALIDATE_EMAIL);
        }
        return $this->user_data;
    }
}

