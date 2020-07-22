<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * @method static string safe() Retorna todos los Users en JSON serializádo.
 */
class User extends Entity
{
    /**
     * Nombre de la tabla que representa esta entidad.
     * @var string
     */
    protected $table = 'users';

    /**
     * Parámetros permitidos al usar asignación masiva (Mass Assignment).
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password'];

    /**
     * QueryScope retorna objeto Json con información no sensible
     * de todos los usuarios.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return string
     */
    public function scopeSafe($query)
    {
        return $query->select('id', 'first_name', 'last_name', 'email')->get()->toJson();
    }
}
