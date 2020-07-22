<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Controller
{
    /**
     * @param array|Collection $items elementos a paginar.
     * @param int $perPage numero de elementos por pagina.
     * @return LengthAwarePaginator objeto con elementos de la pagina actual.
     */
    protected function paginate($items, int $perPage = 5): LengthAwarePaginator
    {
        $currentPage = (int)($_GET['page'] ?? 1);
        $currentPage = ($currentPage <= 0) ? 1 : $currentPage;
        $options = [
            'path' => strtok($_SERVER['REQUEST_URI'], '?')
        ];
        if ($items instanceof Collection) {
            $currentPageItems = $items->slice(($currentPage - 1) * $perPage, $perPage);
        } elseif (is_array($items)) {
            $offset = ($currentPage - 1) * $perPage;
            $currentPageItems = array_slice($items, $offset, $perPage);
        } else {
            throw new \InvalidArgumentException("It's not possible to paginate the given items");
        }
        return new LengthAwarePaginator($currentPageItems, count($items), $perPage, $currentPage, $options);
    }
}
