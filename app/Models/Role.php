<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * Representa los roles que pueden tener los usuarios en el sistema.
 * Ejemplos: administrado, administrador, funcionario.
 *
 * @property int $role_id
 * @property string $role_name
 *
 * @method \Illuminate\Database\Eloquent\Collection users()
 */
class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'role_id';

    protected $fillable = ['role_name'];

    /**
     * Relación: Un rol puede tener muchos usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
