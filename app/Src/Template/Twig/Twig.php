<?php

declare(strict_types=1);

namespace App\Src\Template\Twig;

use Twig\Environment;
use App\Src\Template\TemplateEngine;

/**
 * Esta clase es un Adapter de la clase \Twig\Environment solo tiene
 * la definición del método render para cumplir con la interface.
 */
class Twig implements TemplateEngine
{
    private Environment $engine;

    /**
     * @param Environment $engine
     */
    public function __construct(Environment $engine)
    {
        $this->engine = $engine;
        $this->addExtensions();
    }

    /**
     * {@inheritdoc}
     * @param string $template nombre del template.
     * @param array $data variables que necesita el template.
     * @return string html procesado por Twig.
     */
    public function render(string $template, array $data = []): string
    {
        return $this->engine->render(normalizeName($template, 'twig'), $data);
    }
    
    /**
     * Permite registrar extensiones para ampliar la funcionalidad del Engine.
     * @return void
     */
    private function addExtensions(): void
    {
        $this->engine->addExtension(new TwigExtension());
    }
}
