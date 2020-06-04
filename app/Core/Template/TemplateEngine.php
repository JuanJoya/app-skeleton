<?php

declare(strict_types=1);

namespace App\Core\Template;

/**
 * Interface Renderer
 * Cualquier templateEngine que utilicemos debe implementar esta interfaz.
 */
interface TemplateEngine
{
    /**
     * Este método procesa el template y retorna html.
     * @param string $template nombre del template.
     * @param array $data variables que necesita el template.
     * @return string html procesado por el engine.
     */
    public function render(string $template, array $data = []): string;
}
