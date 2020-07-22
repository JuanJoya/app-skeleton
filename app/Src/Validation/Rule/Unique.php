<?php

declare(strict_types=1);

namespace App\Src\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Permite validar que un registro sea único en la base de datos.
 */
class Unique extends AbstractRule
{
    public const MESSAGE = 'The {column} has already been taken';
    public const LABELED_MESSAGE = 'The {label} has already been taken';

    public const OPTION_TABLE = 'table';
    public const OPTION_COLUMN = 'column';
    public const OPTION_EXCEPT = 'except';
    public const OPTION_ID_COLUMN = 'idColumn';

    /**
     * Opciones por defecto.
     * @var array
     */
    protected $options = [
        self::OPTION_TABLE => 'users',
        self::OPTION_COLUMN => 'email',
        self::OPTION_EXCEPT => null,
        self::OPTION_ID_COLUMN => 'id'
    ];

    /**
     * Orden de las opciones.
     * @var array
     */
    protected $optionsIndexMap = [
        0 => self::OPTION_TABLE,
        1 => self::OPTION_COLUMN,
        2 => self::OPTION_EXCEPT,
        3 => self::OPTION_ID_COLUMN
    ];

    public function validate($value, string $valueIdentifier = null): bool
    {
        $this->value = $value;
        $id = $this->getIdFromTable();
        if ($id) {
            $this->success = ($id == $this->options[self::OPTION_EXCEPT]) ? true : false;
        } else {
            $this->success = true;
        }
        return $this->success;
    }

    /**
     * Retorna el id de la tabla ingresada.
     * @return int|null
     */
    private function getIdFromTable(): ?int
    {
        return Capsule::table($this->options[self::OPTION_TABLE])
            ->where($this->options[self::OPTION_COLUMN], '=', $this->value)
            ->value($this->options[self::OPTION_ID_COLUMN]);
    }
}
