<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     * Se puede construir migraciones reversibles con este método.
     * @see https://book.cakephp.org/phinx/0/en/migrations.html
     */
    public function change()
    {
        $table = $this->table('users', ['collation' => 'utf8_spanish_ci', 'signed' => false]);
        $table
            ->addColumn('email', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('first_name', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('last_name', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('created_at', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', [
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP'
            ])
            ->addIndex('email', ['unique' => true])
            ->create();        
    }
}
