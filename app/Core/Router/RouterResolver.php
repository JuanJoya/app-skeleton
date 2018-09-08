<?php declare(strict_types = 1);

namespace App\Core\Router;

use App\Http\Controllers\Controller;
use Phroute\Phroute\HandlerResolverInterface;
use Auryn\Injector;

class RouterResolver implements HandlerResolverInterface
{
    /**
     * @var Injector Contenedor para inyección de dependencias.
     */
    private $container;

    /**
     * RouterResolver constructor.
     * @param Injector $container Contenedor para inyección de dependencias.
     */
    public function __construct(Injector $container)
    {
        $this->container = $container;
    }

    /**
     * Este método se encarga de instanciar las clases que se registren en la
     * propiedad $handler del Route Collector.
     * @param array|object $handler
     * @return array|object
     * @throws \Auryn\InjectionException
     */
    public function resolve($handler)
    {
        /*
         * Cada clase se registra en la forma:
         * $handler = ['App\Http\Controllers\Example', 'method'];
         */
        if (is_array($handler) && is_string($handler[0])) {
            /*
             * Construimos el objeto con el container, internamente este se
             * encarga de resolver todas sus dependencias.
             */
            $object = $this->container->make($handler[0]);

            /*
             * Si se hereda de la clase Controller tendremos acceso a las propiedades
             * $request & $response de otra forma es necesario pedirlas por constructor.
             */
            if ($object instanceof Controller) {
                $object->setRequest($this->container->make('Http\HttpRequest'));
                $object->setResponse($this->container->make('Http\HttpResponse'));
                $object->setViewResponse($this->container->make('App\Core\Responses\View'));
                $object->setJsonResponse($this->container->make('App\Core\Responses\Json'));
            }

            /*
             * Seteamos el objeto ya instanciado en el handler, este sera enviado
             * al dispatcher
             */
            $handler[0] = $object;
        }

        return $handler;
    }
}
