<?php
namespace CustomMVC\User;
use CustomMVC\Core\DBAbstractModel;


class User extends DBAbstractModel {

    public $nombre;
    public $apellido;
    public $email;
    private $clave;
    protected $id;

    public function get_all(){
        try{
            $this->query = "SELECT nombre, apellido, email FROM usuarios";
            $this->get_results_from_query();
            $this->status = 'Usuarios Registrados';
            return $this->rows;
        }
        catch(\Exception $e){
            $this->status = $e->getMessage();
        }
    }

    public function get($user_email='') {
        try{
            if(!empty($user_email)) {
                $this->query = "
                    SELECT      id, nombre, apellido, email, clave
                    FROM        usuarios
                    WHERE       email = :email
                ";
                $this->bind_params = [':email' => $user_email];
                $this->get_results_from_query();
                if(count($this->rows) == 1) {
                    foreach ($this->rows[0] as $propiedad=>$valor) {
                        $this->{$propiedad} = $valor;
                    }
                    $this->status = 'Usuario encontrado';
                } else{
                    $this->status = 'Usuario no encontrado';
                }               
            } else{
                $this->status = 'No sé ha introducido un email valido.';
            }
        }
        catch(\Exception $e){
            $this->status = $e->getMessage();
        }
    }

    public function set($user_data=array()) {
        try{
            if(!empty($user_data['email'])) {
                $this->get($user_data['email']);
                if($user_data['email'] != $this->email) {
                    foreach ($user_data as $campo=>$valor) {
                        ${$campo} = $valor;
                    }
                    $this->query = "
                            INSERT INTO     usuarios
                            (nombre, apellido, email, clave)
                            VALUES
                            (:name, :lastname, :email, :pass)
                    ";
                    $this->bind_params = [
                        ':name' => $nombre, 
                        ':lastname' => $apellido, 
                        ':email' => $email, 
                        ':pass' => password_hash($clave, PASSWORD_DEFAULT),
                    ];
                    $this->execute_single_query();
                    $this->status = 'Usuario agregado exitosamente';
                } else {
                    $this->status = 'El usuario ya existe';
                }
            } else {
                $this->status = 'No sé ha introducido un email valido.';
            }
        }
        catch(\Exception $e){
            $this->status = $e->getMessage();
        }
    }

    public function edit($user_data=array()) {
        try{
            if(!empty($user_data['email'])){
                foreach ($user_data as $campo=>$valor) {
                    ${$campo} = $valor;
                }
                $this->query = "
                        UPDATE      usuarios
                        SET         nombre = :name,
                                    apellido = :lastname
                        WHERE       email = :email
                ";
                $this->bind_params = [
                    ':name' => $nombre, 
                    ':lastname' => $apellido, 
                    ':email' => $email, 
                ];        
                $this->execute_single_query();
                if($this->affected_rows){
                    $this->status = 'Usuario modificado';
                }else{
                    $this->status = 'Usuario no encontrado';
                }  
            }else{
                $this->status = 'No sé ha introducido un email valido.';
            }
        }
        catch(\Exception $e){
            $this->status = $e->getMessage();
        }   
    }

    public function delete($user_data=array()) {
        try{
            if(!empty($user_data['email']))
            {
                $this->query = "
                        DELETE FROM     usuarios
                        WHERE           email = :email
                ";
                $this->bind_params = [':email' => $user_data['email']];
                $this->execute_single_query();
                if($this->affected_rows){
                    $this->status = 'Usuario eliminado';
                }else{
                    $this->status = 'Usuario no encontrado';
                }
            }else{
                $this->status = 'No sé ha introducido un email valido.';
            }
        }
        catch(\Exception $e){
            $this->status = $e->getMessage();
        }   
    }

    function __construct() {
        $this->db_name = 'test';
        
    }

    function __destruct() {
        unset($this);
    }
}
