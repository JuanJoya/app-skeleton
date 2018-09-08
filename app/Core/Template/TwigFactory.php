<?php declare(strict_types = 1);

namespace App\Core\Template;

use Twig_Environment;
use Twig_Function;
use Twig_Loader_Filesystem;

/**
 * Class TwigFactory
 * @package App\Core\Template
 * Esta clase prepara el objeto Twig_Environment para el container IoC.
 */
class TwigFactory
{
    /**
     * @var Twig_Loader_Filesystem
     */
    private $loader;

    /**
     * @var Twig_Environment
     */
    private $environment;

    public function __construct()
    {
        $this->loader = new Twig_Loader_Filesystem(dirname(__DIR__) . '/../../templates');
        $this->environment = new Twig_Environment($this->loader);
        $this->setHelpers();
    }

    /**
     * @return Twig_Environment
     */
    public function __invoke(): Twig_Environment
    {
        return $this->environment;
    }

    /**
     * En este método se pueden registrar funciones, filtros, tags, objetos...
     * para que estén disponibles en los templates.
     * @return void
     * @see https://twig.symfony.com/doc/2.x/advanced.html
     */
    private function setHelpers(): void
    {
        $this->environment->addFunction(
            new Twig_Function('url', 'getUrl')
        );
    }
}
