<?php

declare(strict_types=1);

namespace App\Src\Provider;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Src\Template\{TemplateEngine, Twig\Twig};
use League\Container\ServiceProvider\AbstractServiceProvider;

class TemplateServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        Twig::class,
        TemplateEngine::class
    ];

    /**
     * @return void
     */
    public function register(): void
    {
        /**
         * ---------------------------------------------------------------------------------------
         * Template Engine
         * ---------------------------------------------------------------------------------------
         * Un Template Engine es un mecanismo para procesar (compilar) ficheros conocidos como
         * templates, estos ficheros están escritos en un lenguaje mas seguro que protege de XSS
         * Scripting, en estos ficheros se coloca el código de la capa de presentación (Views).
         * ---------------------------------------------------------------------------------------
         * @see https://twig.symfony.com/doc/3.x/
         */
        $loader = new FilesystemLoader(ROOT_PATH . '/resources/views');
        $environment = new Environment($loader);
        $twig = new Twig($environment);
        $this->getLeagueContainer()->add(Twig::class, $twig);
        $this->getLeagueContainer()->add(TemplateEngine::class, $twig);
    }
}
