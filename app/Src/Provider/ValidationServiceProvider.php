<?php

declare(strict_types=1);

namespace App\Src\Provider;

use Sirius\Validation\RuleFactory;
use App\Src\Validation\Rule\{Unique, Exists};
use League\Container\ServiceProvider\AbstractServiceProvider;

class ValidationServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        RuleFactory::class
    ];

    /**
     * @return void
     */
    public function register(): void
    {
        /**
         * ---------------------------------------------------------------------------------------
         * Validation - Rule Factory
         * ---------------------------------------------------------------------------------------
         * Este provider permite configurar el objeto de validación, con este objeto se pueden
         * validar los datos que se obtienen del Request, aquí se pueden registrar reglas de
         * validación personalizadas para extender la funcionalidad.
         * ---------------------------------------------------------------------------------------
         * @see https://www.sirius.ro/php/sirius/validation/rule_factory.html
         */
        $this->getLeagueContainer()->add(RuleFactory::class, function () {
            $ruleFactory = new RuleFactory();
            $ruleFactory->register('unique', Unique::class);
            $ruleFactory->register('exists', Exists::class);
            return $ruleFactory;
        });
    }
}
