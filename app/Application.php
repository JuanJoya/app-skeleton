<?php

declare(strict_types=1);

namespace App;

use App\Src\Core\Kernel;
use Psr\Http\Message\ServerRequestInterface;

class Application extends Kernel
{
    /**
     * Un ServiceProvider permite configurar cada dependencia antes de almacenarla en el Container, en otras
     * palabras, se le explica al Container la forma en que debe resolver un contrato/dependencia, este método
     * permite registrar cada ServiceProvider, el orden no debería alterar el funcionamiento.
     * @see https://container.thephpleague.com/3.x/service-providers/
     * @return array
     */
    protected function getProviders(): array
    {
        return [
            \App\Src\Provider\HttpMessageServiceProvider::class,
            \App\Src\Provider\RouterServiceProvider::class,
            \App\Src\Provider\SapiEmitterServiceProvider::class,
            \App\Src\Provider\TemplateServiceProvider::class,
            \App\Src\Provider\DatabaseServiceProvider::class,
            \App\Src\Provider\ValidationServiceProvider::class,
            \App\Src\Provider\SessionServiceProvider::class
        ];
    }


    /**
     * Un Middleware puede manipular un Request o un Response en diferentes capas de la aplicación, este método permite
     * registrar los Middleware de la aplicación. El orden de la pila importa, el primer Middleware generalmente ejecuta
     * a los demás, captura e imprime el Response. Se admite el uso de condiciones.
     * @see https://github.com/oscarotero/middleland
     * @return array
     */
    protected function getAppMiddleware(ServerRequestInterface $request): array
    {
        return [
            \App\Src\Middleware\EmitterMiddleware::class,
            [APP_DEBUG, new \Middlewares\Whoops()],
            \PSR7Sessions\Storageless\Http\SessionMiddleware::class,
            ...$this->vendorMiddleware(),
            \App\Src\Middleware\RouterMiddleware::class
        ];
    }

    /**
     * En este método se registran los custom Middleware.
     * @return array
     */
    protected function vendorMiddleware(): array
    {
        return [
            //Custom App Middleware
        ];
    }
}
