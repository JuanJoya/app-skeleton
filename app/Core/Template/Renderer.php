<?php declare(strict_types = 1);

namespace App\Core\Template;

/**
 * Interface Renderer
 * @package App\Core\Template
 * Cualquier templateEngine que utilicemos debe implementar esta interfaz.
 */
interface Renderer
{
    /**
     * Este método procesa el lenguaje del template y retorna una salida html.
     * @param string $template nombre del template
     * @param array $data variables que necesita el template
     * @return string
     */
    public function render(string $template, array $data = []) : string;
}
