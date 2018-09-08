<?php declare(strict_types = 1);

namespace App\Core\Responses;

use Http\Response;

class Json
{
    /**
     * @var Response
     */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @param array $data datos a serializar
     * @param int $status código de status http
     * @return Response
     */
    public function make(array $data, int $status = 200)
    {
        $this->response->setStatusCode($status);
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setContent(
            json_encode($data)
        );

        return $this->response;
    }
}
