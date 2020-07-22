<?php

declare(strict_types=1);

namespace App\Src\Middleware;

use League\Route\Router;
use App\Src\Response\Html;
use League\Route\Http\Exception\HttpExceptionInterface;
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use Psr\Http\Message\{ResponseFactoryInterface, ResponseInterface, ServerRequestInterface};

/**
 * Este Middleware ejecuta el dispatch del objeto Router, internamente se ejecuta el handler correspondiente a la
 * ruta y se obtiene un objeto Response.
 */
class RouterMiddleware implements MiddlewareInterface
{
    private Router $router;
    private ResponseFactoryInterface $responseFactory;

    /**
     * @param Router $router
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(Router $router, ResponseFactoryInterface $responseFactory)
    {
        $this->router = $router;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (APP_DEBUG) {
            return $this->router->dispatch($request);
        }
        return $this->dispatchRoute($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    private function dispatchRoute(ServerRequestInterface $request): ResponseInterface
    {
        try {
            return $this->router->dispatch($request);
        } catch (HttpExceptionInterface $e) {
            $response = $this->responseFactory->createResponse($e->getStatusCode());
            return Html::response(
                $response,
                'errors.http',
                ['status' => $response->getStatusCode(), 'message' => $response->getReasonPhrase()]
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->createResponse(500);
        }
    }
}
