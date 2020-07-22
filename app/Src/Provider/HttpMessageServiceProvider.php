<?php

declare(strict_types=1);

namespace App\Src\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Laminas\Diactoros\{Response, ResponseFactory, ServerRequestFactory};
use Psr\Http\Message\{ResponseInterface, ResponseFactoryInterface, ServerRequestInterface};

class HttpMessageServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        ServerRequestInterface::class,
        ResponseInterface::class,
        ResponseFactoryInterface::class
    ];

    /**
     * @return void
     */
    public function register(): void
    {
        /**
         * ---------------------------------------------------------------------------------------
         * PSR-7 HTTP message interfaces
         * ---------------------------------------------------------------------------------------
         * Este provider le explica al Container como resolver las interfaces para implementar
         * PSR-7, este ultimo es un estándar para representar mensajes HTTP. Encapsula la creación
         * y comportamiento de los objetos Request y Response, base del desarrollo web moderno.
         * ---------------------------------------------------------------------------------------
         * @see https://www.php-fig.org/psr/psr-7/
         * @see https://github.com/laminas/laminas-diactoros
         */

        $this->getLeagueContainer()->share(
            ServerRequestInterface::class,
            ServerRequestFactory::fromGlobals()
        );
        $this->getLeagueContainer()->share(
            ResponseInterface::class,
            Response::class
        );
        $this->getLeagueContainer()->add(
            ResponseFactoryInterface::class,
            ResponseFactory::class
        );
    }
}
