<?php

declare(strict_types=1);

namespace App\Src\Response;

use App\Src\Template\TemplateEngine;
use Psr\Http\Message\ResponseInterface;

/**
 * Esta clase construye un Response a partir de un template compilado con el Engine,
 * Hay protección contra ataques XSS Cross-site scripting.
 */
class View
{
    private ResponseInterface $response;
    private TemplateEngine $engine;

    /**
     * @param ResponseInterface $response
     * @param TemplateEngine $engine
     */
    public function __construct(ResponseInterface $response, TemplateEngine $engine)
    {
        $this->response = $response;
        $this->engine = $engine;
    }

    /**
     * @param string $template nombre del template.
     * @param array $params parámetros a enviar al template.
     * @param int $status código de status http.
     * @return ResponseInterface
     */
    public function make(string $template, array $params = [], int $status = 200): ResponseInterface
    {
        $this->response->withStatus($status);
        $this->response->withHeader('Content-Type', 'text/html; charset=utf-8');
        $this->response->getBody()->write(
            $this->engine->render($template, $params)
        );
        return $this->response;
    }
}
