<?php declare(strict_types = 1);

namespace App\Core\Template;

use Twig_Environment;

class TwigRenderer implements Renderer
{
    /**
     * @var Twig_Environment
     */
    private $renderEngine;

    /**
     * TwigRenderer constructor.
     * @param Twig_Environment $renderer
     */
    public function __construct(Twig_Environment $renderer)
    {
        $this->renderEngine = $renderer;
    }

    /**
     * @param string $template nombre del template
     * @param array $data variables que necesita el template
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $template, array $data = []) : string
    {
        return $this->renderEngine->render(
            resolveExtension($template, 'twig'),
            $data
        );
    }
}
