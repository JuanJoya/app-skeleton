<?php

declare(strict_types=1);

namespace App\Src\Core;

use Middleland\Dispatcher;
use League\Container\Container;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

abstract class Kernel
{
    protected static Container $app;
    private Container $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = static::setContainer($container);
    }

    /**
     * Ejecuta la pila de Middleware, se asume que hay un Emitter registrado para imprimir el Response.
     * @param ServerRequestInterface $request
     * @return void
     */
    public function run(ServerRequestInterface $request): void
    {
        $this->dispatch($request);
    }

    /**
     * PSR-15 Middleware Dispatcher, enviá un Request a traves de una pila de Middleware y retorna un Response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $dispatcher = new Dispatcher(
            $this->getAppMiddleware($request),
            $this->container
        );
        return $dispatcher->handle($request);
    }

    /**
     * Permite cargar todos los ServiceProviders registrados en la aplicación.
     * @return void
     */
    public function bootServices(): void
    {
        foreach ($this->getProviders() as $provider) {
            $this->container->addServiceProvider($provider);
        }
    }

    /**
     * Permite obtener el Container IoC.
     * @return Container
     */
    public static function getContainer(): Container
    {
        if (is_null(static::$app)) {
            throw new \RuntimeException('There is no container configured yet.');
        }
        return static::$app;
    }

    /**
     * Almacena el Container en una variable estática para que este disponible de forma global.
     * @param Container $container
     * @return Container
     */
    private static function setContainer(Container $container): Container
    {
        return static::$app = $container;
    }

    /**
     * Listado de Service Providers para registrar en el Container.
     * @return array
     */
    abstract protected function getProviders(): array;

    /**
     * Middleware que se aplican de forma global en la aplicación.
     * @return array
     */
    abstract protected function getAppMiddleware(ServerRequestInterface $request): array;
}
