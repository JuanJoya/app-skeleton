<?php

declare(strict_types=1);

namespace App\Core\Template\Twig;

use Twig\TwigFunction as Func;
use Twig\Extension\AbstractExtension;

/**
 * Esta clase permite el registro de funciones, filtros, tags, objetos,
 * para que estén disponibles en los templates.
 * @see https://twig.symfony.com/doc/3.x/advanced.html#creating-an-extension
 */
class TwigExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new Func('url', 'getUrl')
        ];
    }
}
