<?php

namespace CustomMVC\User;

class User
{
	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
    private $firstName;
    /**
     * @var string
     */
    private $lastName;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $password;
    
    /**
     * @param string $email correo con formato valido
     * @param string $password hash de una contraseña
     * @param int $id
     */
    public function __construct($email, $password, $id = null)
    {
        $this->setEmail($email);
        $this->setPassword($password);
        
        if(is_int($id)) {
            $this->id = $id;
        }   
    }

    /**
     * Helper para crear User desde resultSet
     * @param array $params
     * @return User
     */
    public static function create(array $params)
    {
        $user = new User(
            $params['email'], $params['password'], (int)$params['id']
        );
        $user->setName($params['first_name'], $params['last_name']);

        return $user;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(
                "Invalid email address: [$email]"
            );
        }
        $this->email = $email;
    }

    /**
     * @param string $pass
     */
    public function setPassword($pass)
    {
        if(! filter_var($pass, FILTER_UNSAFE_RAW)) {
            throw new \InvalidArgumentException(
                "Empty password"
            );
        }
        $this->password = $pass;
    }

        /**
     * @param string $firstName
     * @param string $lastName
     */
    public function setName($firstName = null, $lastName = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
    	return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
