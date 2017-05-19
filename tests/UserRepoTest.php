<?php

use CustomMVC\User\User;
use CustomMVC\User\UserRepository;

class UserRepoTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var UserRepository
     */
    public $userModel;

    function __construct()
    {
        $this->userModel = new UserRepository();
    }

    function test_get_all_users()
    {
        $users = $this->userModel->all();

        foreach ($users as $user){
            $this->assertInstanceOf(User::class, $user);
        }
    }

    function test_find_user()
    {
        $userData = [
            'first_name'    => 'name test',
            'last_name'  => 'lastname test',
            'email'     => 'test@mailpro.co',
            'password'     => 'pa$$w0rd',
        ];

        $this->create_user($userData);

        $user = $this->userModel->get('test@mailpro.co');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@mailpro.co', $user->getEmail());

        $this->delete_user('test@mailpro.co');
    }

    function create_user(array $userData)
    {
        $this->userModel->set($userData);
    }

    function delete_user($userEmail)
    {
        $this->userModel->delete($userEmail);
    }
}
