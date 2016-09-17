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
     * @param string $email    correo con formato valido
     * @param string $password hash de una contraseña
     * @param int    $id       id del usuario en la base de datos
     */
    public function __construct($email, $password, $id = null)
    {
        if(!is_string($email) || !is_string($password)) {
            throw new \InvalidArgumentException();
        }

        $this->email = $email;
        $this->password = $password;
        
        if(is_int($id)) {
            $this->id = $id;
        }   
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
    public function getEmail(){
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
