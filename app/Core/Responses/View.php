<?php declare(strict_types = 1);

namespace App\Core\Responses;

use App\Core\Template\Renderer;
use Http\Response;

class View
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var Renderer
     */
    private $renderer;

    public function __construct(Response $response, Renderer $renderer)
    {
        $this->response = $response;
        $this->renderer = $renderer;
    }

    /**
     * @param string $template nombre del template
     * @param array $data variables que necesita el template
     * @param int $status código de status http
     * @return Response
     */
    public function make(string $template, array $data = [], int $status = 200)
    {
        $this->response->setStatusCode($status);
        $this->response->setContent(
            $this->renderer->render($template, $data)
        );

        return $this->response;
    }
}
