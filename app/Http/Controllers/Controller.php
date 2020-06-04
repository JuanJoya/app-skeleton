<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Responses\Json;
use App\Core\Responses\View;
use Http\Request as Request;
use Http\Response as Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var View
     */
    protected $view;

    /**
     * @var Json
     */
    protected $json;

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @param View $view
     */
    public function setViewResponse(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param Json $json
     */
    public function setJsonResponse(Json $json)
    {
        $this->json = $json;
    }

    /**
     * @param array|Collection $items elementos a paginar.
     * @param int $perPage numero de elementos por pagina.
     * @return LengthAwarePaginator objeto con elementos de la pagina actual.
     */
    protected function paginate($items, $perPage = 5)
    {
        //Se obtiene el parámetro 'page' del query string.
        $currentPage = (int)($_GET['page'] ?? 1);

        //El path que utilizan los links de la paginación.
        $options = [
            'path' => strtok($_SERVER['REQUEST_URI'], '?')
        ];

        //Se valida que la pagina actual no sea un numero negativo.
        $currentPage = ($currentPage <= 0) ? 1 : $currentPage;

        /*
         * El método Slice permite extraer de la colección|array un numero de elementos n($perPage)
         * desde la posición x($offset).
         */
        if ($items instanceof Collection) {
            $currentPageItems = $items->slice(($currentPage - 1) * $perPage, $perPage);
        } elseif (is_array($items)) {
            $offset = ($currentPage - 1) * $perPage;
            $currentPageItems = array_slice($items, $offset, $perPage);
        } else {
            throw new \InvalidArgumentException("It's not possible to paginate the given items");
        }

        //Este objeto encapsula los elementos que se han de mostrar en la pagina actual.
        return new LengthAwarePaginator($currentPageItems, count($items), $perPage, $currentPage, $options);
    }
}
