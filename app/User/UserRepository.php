<?php

namespace CustomMVC\User;
use CustomMVC\Core\DBAbstractModel;

class UserRepository extends DBAbstractModel 
{
    /**
     * @var string mensaje del estado de la app
     */
    public $status;

    /**
     * @return array
     * permite consultar todos los usuarios
     */
    public function all()
    {
        try {
            $this->query = "SELECT * FROM users";
            $this->getResultsFromQuery();
            $data = $this->mapToUsers($this->rows);

            if (empty($data)) {
                $this->status = 'No hay Registros.';
                return null;
            } else {
                $this->status = 'Usuarios Registrados';
                return $data;
            }
        } catch(\Exception $e) {

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
    public function get($userEmail)
    {
        try {
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
        } catch(\Exception $e) {

            $this->status = $e->getMessage();
            return null;
        }
    }

    /**
     * @param array $userData información que proviene de la vista para crear un usuario
     * permite guardar un usuario en la DB
     */
    public function set(array $userData)
    {
        try {
            if(!empty($userData['email'])) {
                $anUser = $this->get($userData['email']);
                $anUser ? $anUser = true : $anUser = false;

                if(!$anUser) {
                    $this->query = "
                            INSERT INTO users
                              (first_name, last_name, email, password)
                            VALUES
                              (:first_name, :last_name, :email, :password)
                    ";
                    $this->bindParams = [
                        ':first_name' => $userData['first_name'],
                        ':last_name' => $userData['last_name'],
                        ':email' => $userData['email'],
                        ':password' => password_hash($userData['password'], PASSWORD_DEFAULT),
                    ];
                    $this->executeSingleQuery();
                    $this->status = 'Usuario agregado exitosamente';
                } else {
                    $this->status = 'El usuario ya existe';
                }
            } else {
                $this->status = 'No sé ha introducido un email valido.';
            }
        } catch(\Exception $e) {
            $this->status = $e->getMessage();
        }
    }

    /**
     * @param array $userData información que proviene de la vista para modificar un usuario
     */
    public function edit(array $userData)
    {
        try {
            if(!empty($userData['email'])) {
                $this->query = "
                        UPDATE      users
                        SET         first_name = :first_name,
                                    last_name = :last_name
                        WHERE       email = :email
                ";
                $this->bindParams = [
                    ':first_name' => $userData['first_name'],
                    ':last_name' => $userData['last_name'],
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
        } catch(\Exception $e) {
            $this->status = $e->getMessage();
        }   
    }

    /**
     * @param string $userEmail email del usuario a eliminar
     */
    public function delete($userEmail='')
    {
        try {
            if(!empty($userEmail)) {
                $this->query = "
                        DELETE FROM     users
                        WHERE           email = :email
                ";
                $this->bindParams = [':email' => $userEmail];
                $this->executeSingleQuery();

                if($this->affectedRows) {
                    $this->status = 'Usuario eliminado';
                } else {
                    $this->status = 'Usuario no encontrado';
                }
            } else {
                $this->status = 'No sé ha introducido un email valido.';
            }
        } catch(\Exception $e) {
            $this->status = $e->getMessage();
        }   
    }

    /**
     * @param array $results array $rows con los usuarios
     * @return array con instancias de User
     * permite modelar un array de usuarios
     */
    private function mapToUsers(array $results)
    {
        $users = array();
        foreach ($results as $result) {
            $user = $this->mapEntity($result);
            $users[] = $user;
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
