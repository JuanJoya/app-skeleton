<?php
use CustomMVC\User\User;
class UserTest extends PHPUnit_Framework_TestCase
{

    function test_construct()
    {
        $user = new User('email@example.com', '123456');
        $this->assertInstanceOf(User::class, $user);
    }

    function test_fail_to_construct()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $user = new User(1,2,3);
    }

    function test_params()
    {
        $user = new User('email@example.com', '123456', 15);
        $user->setName('juan', 'joya');

        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $id = $user->getId();

        $this->assertEquals('juan', $firstName);
        $this->assertEquals('joya', $lastName);
        $this->assertEquals(15, $id);
    }

}