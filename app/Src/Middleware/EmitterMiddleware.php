<?php

declare(strict_types=1);

namespace App\Src\Middleware;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use Laminas\HttpHandlerRunner\Emitter\{EmitterInterface, SapiEmitter};

use function headers_sent;

/**
 * Este Middleware se encarga de capturar y emitir el objeto Response.
 * @see https://github.com/woohoolabs/harmony
 */
class EmitterMiddleware implements MiddlewareInterface
{
    protected EmitterInterface $emitter;
    protected bool $checkOutputStart;

    /**
     * @param EmitterInterface|null $emitter
     * @param bool $checkOutputStart
     */
    public function __construct(?EmitterInterface $emitter = null, bool $checkOutputStart = false)
    {
        $this->emitter = $emitter ?? new SapiEmitter();
        $this->checkOutputStart = $checkOutputStart;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($this->checkOutputStart === false || headers_sent() === false) {
            $this->emitter->emit($response);
        }
        return $response;
    }

    /**
     * @return EmitterInterface
     */
    public function getEmitter(): EmitterInterface
    {
        return $this->emitter;
    }

    /**
     * @param EmitterInterface $emitter
     * @return void
     */
    public function setEmitter(EmitterInterface $emitter): void
    {
        $this->emitter = $emitter;
    }

    /**
     * @return bool
     */
    public function isOutputStartChecked(): bool
    {
        return $this->checkOutputStart;
    }

    /**
     * @param bool $checkOutputStart
     * @return void
     */
    public function setCheckOutputStart(bool $checkOutputStart): void
    {
        $this->checkOutputStart = $checkOutputStart;
    }
}
