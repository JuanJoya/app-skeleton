<?php

declare(strict_types=1);

namespace App\Src\Provider;

use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Container\ServiceProvider\AbstractServiceProvider;

class RouterServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [Router::class];

    /**
     * @return void
     */
    public function register(): void
    {
        /**
         * ---------------------------------------------------------------------------------------
         * Application Strategy
         * ---------------------------------------------------------------------------------------
         * Un Application Strategy define la forma en que una ruta es despachada en el Router, en
         * este caso la estrategia es resolver las dependencias del handler (Controller) mediante
         * el Container registrado en la aplicación.
         * ---------------------------------------------------------------------------------------
         */
        $strategy = new ApplicationStrategy();
        $strategy->setContainer($this->getLeagueContainer());

        /**
         * ---------------------------------------------------------------------------------------
         * PSR-7 & PSR-15 Router
         * ---------------------------------------------------------------------------------------
         * Un Router es un objeto que permite mapear los endpoints de una aplicación y asociarlos
         * con un handler, este handler implementa PSR-7 por lo tanto recibe un Request y retorna
         * un Response, también se admite el uso de Middleware que implementen PSR-15.
         * ---------------------------------------------------------------------------------------
         * @see https://route.thephpleague.com/4.x/
         */
        $router = new Router();
        $router->setStrategy($strategy);
        
        /**
         * Este archivo incluye la definición de todas las rutas de nuestra aplicación, se incluye
         * dentro de una función anónima para limitar el scope del 'require' a solo el Router.
         */
        (function () use ($router) {
            require ROOT_PATH . '/app/Http/routes.php';
        })();
        
        $this->getLeagueContainer()->share(Router::class, $router);
    }
}
