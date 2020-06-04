<?php

use Phinx\Seed\AbstractSeed;

class UserTableSeeder extends AbstractSeed
{
    /**
     * Run Method.
     * Se pueden construir Seeders (datos de prueba) con este método.
     * @see https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run($data = [])
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $data[] = [
                'email' => $faker->email,
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName
            ];
        }
        $this->table('users')->insert($data)->saveData();
    }
}
