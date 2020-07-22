<?php

declare(strict_types=1);

namespace App\Src\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Permite validar que un registro exista en la base de datos.
 */
class Exists extends AbstractRule
{
    public const MESSAGE = 'The selected {column} is invalid.';
    public const LABELED_MESSAGE = 'The selected {label} is invalid.';

    public const OPTION_TABLE = 'table';
    public const OPTION_COLUMN = 'column';

    /**
     * Opciones por defecto.
     * @var array
     */
    protected $options = [
        self::OPTION_TABLE => 'users',
        self::OPTION_COLUMN => 'id'
    ];

    /**
     * Orden de las opciones.
     * @var array
     */
    protected $optionsIndexMap = [
        0 => self::OPTION_TABLE,
        1 => self::OPTION_COLUMN
    ];

    public function validate($value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        return $this->success = $this->verifyIfColumnExist();
    }

    /**
     * Verifica si existe(true) o no(false) un registro en la base de datos.
     * @return bool
     */
    private function verifyIfColumnExist(): bool
    {
        $result = Capsule::table($this->options[self::OPTION_TABLE])
            ->where($this->options[self::OPTION_COLUMN], '=', $this->value)
            ->value($this->options[self::OPTION_COLUMN]);
        return !empty($result);
    }
}
