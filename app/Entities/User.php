<?php

namespace App\Entities;

/**
 * Class User
 * @package App\Entities
 * @method static string safe() Retorna todos los Users en JSON serializádo.
 */
class User extends Entity
{
    /**
     * @var string nombre de la tabla que representa esta entidad.
     */
    protected $table = 'users';

    /**
     * @var array parámetros http permitidos.
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password'];

    /**
     * QueryScope retorna objeto Json con información no sensible
     * de todos los usuarios.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return string
     */
    public function scopeSafe($query)
    {
        return $query->select('id', 'first_name', 'last_name', 'email')->get()->toJson();
    }
}
