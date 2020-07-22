<?php

declare(strict_types=1);

namespace App\Src\Provider;

use Illuminate\Pagination\Paginator;
use Illuminate\Database\Capsule\Manager as Capsule;
use League\Container\ServiceProvider\{AbstractServiceProvider, BootableServiceProviderInterface};

class DatabaseServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * @var array
     */
    protected $provides = [Capsule::class];

    /**
     * @return void
     */
    public function boot(): void
    {

        /**
         * ---------------------------------------------------------------------------------------
         * Database ORM
         * ---------------------------------------------------------------------------------------
         * Object-Relational-Mapping es una técnica de programación que convierte las tablas de
         * una base de datos relacional en entidades para trabajar en un modelo de datos orientado
         * a objetos.
         * ---------------------------------------------------------------------------------------
         */
        $capsule = new Capsule();

        /**
         * ---------------------------------------------------------------------------------------
         * Este provider crea el objeto Capsule, este permite configurar fácilmente la conexión
         * a la base de datos y preparar el ORM. Hecho esto es posible utilizar el Query Builder,
         * el Schema Builder y los modelos de Eloquent.
         * ---------------------------------------------------------------------------------------
         * @see https://laravel.com/docs/5.1/eloquent
         */
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => env('DB_HOST'),
            'database'  => env('DB_NAME'),
            'username'  => env('DB_USER'),
            'password'  => env('DB_PASS'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        /**
         * Permite utilizar el objeto Capsule de forma global mediante métodos estáticos.
         */
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        /**
         * Configuración básica de la paginación.
         */
        Paginator::currentPathResolver(function () {
            return isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : '/';
        });

        Paginator::currentPageResolver(function ($pageName = 'page') {
            $page = isset($_REQUEST[$pageName]) ? $_REQUEST[$pageName] : 1;
            return $page;
        });

        $this->getLeagueContainer()->share(Capsule::class, $capsule);
    }

    /**
     * @return void
     */
    public function register(): void
    {
        // ...
    }
}
