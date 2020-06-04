<?php

declare(strict_types=1);

namespace App\Core\Responses;

use App\Core\Template\TemplateEngine;
use Http\Response;

/**
 * Esta clase construye un Response a partir de un template compilado con el Engine,
 * Hay protección contra ataques XSS Cross-site scripting.
 */
class View
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var TemplateEngine
     */
    private $engine;

    public function __construct(Response $response, TemplateEngine $engine)
    {
        $this->response = $response;
        $this->engine = $engine;
    }

    /**
     * @param string $template nombre del template.
     * @param array $params parámetros a enviar al template.
     * @param int $status código de status http.
     * @return Response
     */
    public function make(string $template, array $params = [], int $status = 200): Response
    {
        $this->response->setStatusCode($status);
        $this->response->setContent(
            $this->engine->render($template, $params)
        );
        return $this->response;
    }
}
