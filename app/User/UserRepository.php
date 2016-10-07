<?php

namespace CustomMVC\User;
use CustomMVC\Core\DBAbstractModel;
use Illuminate\Support\Collection;

class UserRepository extends DBAbstractModel 
{
    /**
     * @var string mensaje del estado de la app
     */
    public $status;

    /**
     * @return Collection|null
     * permite consultar todos los usuarios
     */
    public function all()
    {
        try
        {
            $this->query = "SELECT * FROM users";
            $this->getResultsFromQuery();
            $this->status = 'Usuarios Registrados';
            $data = $this->mapToUsers($this->rows);

            return $data;
        }
        catch(\Exception $e)
        {
            $this->status = $e->getMessage();
            return null;
        }
    }

    /**
     * @param string $userEmail
     * @return User|null instancia de User con la información del usuario
     * implementación del método abstracto, permite consultar un usuario
     * modela el resultado en una instancia de User
     */
    public function get($userEmail='')
    {
        try
        {
            if(!empty($userEmail)) {
                $this->query = "
                    SELECT      id, first_name, last_name, email, password
                    FROM        users
                    WHERE       email = :email
                ";
                $this->bindParams = [':email' => $userEmail];
                $this->getResultsFromQuery();

                if(count($this->rows) == 1) {
                    $this->status = 'Usuario encontrado';
                    $data = $this->mapEntity(array_shift($this->rows));     
                    return $data;
                } else {
                    $this->status = 'Usuario no encontrado';
                    return null;
                }               
            } else {
                $this->status = 'No sé ha introducido un email valido.';
                return null;
            }
        }
        catch(\Exception $e)
        {
            $this->status = $e->getMessage();
        }
    }

    /**
     * @param array $userData información que proviene de la vista para crear un usuario
     * permite guardar un usuario en la DB
     */
    public function set($userData=array())
    {
        try
        {
            if(!empty($userData['email'])) {
                $anUser = $this->get($userData['email']);
                $anUser ? $userEmail = $anUser->getEmail() : $userEmail = '';

                if($userData['email'] != $userEmail) {
                    $this->query = "
                            INSERT INTO     users
                            (first_name, last_name, email, password)
                            VALUES
                            (:first_name, :last_name, :email, :password)
                    ";
                    $this->bindParams = [
                        ':first_name' => $userData['nombre'],
                        ':last_name' => $userData['apellido'],
                        ':email' => $userData['email'],
                        ':password' => password_hash($userData['clave'], PASSWORD_DEFAULT),
                    ];
                    $this->executeSingleQuery();
                    $this->status = 'Usuario agregado exitosamente';
                } else {
                    $this->status = 'El usuario ya existe';
                }
            } else {
                $this->status = 'No sé ha introducido un email valido.';
            }
        }
        catch(\Exception $e)
        {
            $this->status = $e->getMessage();
        }
    }

    /**
     * @param array $userData información que proviene de la vista para modificar un usuario
     */
    public function edit($userData=array())
    {
        try
        {
            if(!empty($userData['email'])) {
                $this->query = "
                        UPDATE      users
                        SET         first_name = :first_name,
                                    last_name = :last_name
                        WHERE       email = :email
                ";
                $this->bindParams = [
                    ':first_name' => $userData['nombre'],
                    ':last_name' => $userData['apellido'],
                    ':email' => $userData['email'],
                ];        
                $this->executeSingleQuery();

                if($this->affectedRows) {
                    $this->status = 'Usuario modificado';
                } else {
                    $this->status = 'Usuario no encontrado';
                }  
            } else {
                $this->status = 'No sé ha introducido un email valido.';
            }
        }
        catch(\Exception $e)
        {
            $this->status = $e->getMessage();
        }   
    }

    /**
     * @param array $userEmail array con el email del usuario a eliminar
     */
    public function delete($userEmail=array())
    {
        try
        {
            if(!empty($userEmail['email'])) {
                $this->query = "
                        DELETE FROM     users
                        WHERE           email = :email
                ";
                $this->bindParams = [':email' => $userEmail['email']];
                $this->executeSingleQuery();

                if($this->affectedRows) {
                    $this->status = 'Usuario eliminado';
                } else {
                    $this->status = 'Usuario no encontrado';
                }
            } else {
                $this->status = 'No sé ha introducido un email valido.';
            }
        }
        catch(\Exception $e)
        {
            $this->status = $e->getMessage();
        }   
    }

    /**
     * @param array $results array $rows con los usuarios
     * @return Collection con instancias de User
     * permite modelar una colección de usuarios
     */
    private function mapToUsers(array $results)
    {
        $users = new Collection();
        foreach ($results as $result) {
            $user = $this->mapEntity($result);
            $users->push($user);
        }
        return $users;
    }

    /**
     * @param array $result contiene los datos de un usuario
     * @return User instancia de un usuario
     */
    private function mapEntity(array $result)
    {
        $user = new User(
            $result['email'],
            $result['password'],
            (int)$result['id']
        );
        $user->setName(
            $result['first_name'],
            $result['last_name']
        );
        return $user;
    }
}
