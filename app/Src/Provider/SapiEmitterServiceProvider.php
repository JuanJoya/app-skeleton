<?php

declare(strict_types=1);

namespace App\Src\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Laminas\HttpHandlerRunner\Emitter\{EmitterInterface, SapiEmitter};

class SapiEmitterServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [EmitterInterface::class];

    /**
     * @return void
     */
    public function register(): void
    {
        /**
         * ---------------------------------------------------------------------------------------
         * PSR-7 Emitter
         * ---------------------------------------------------------------------------------------
         * Un Emitter es un objeto que realiza tres acciones fundamentales con el objeto Response,
         * envía los headers, envía el status code e imprime el Response body, internamente se
         * utilizan funciones de PHP como header() y echo().
         * ---------------------------------------------------------------------------------------
         * @see https://docs.laminas.dev/laminas-httphandlerrunner/emitters/
         */
        $this->getLeagueContainer()->add(EmitterInterface::class, SapiEmitter::class);
    }
}
