<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Country
 *
 * Representa los países, usados tanto para la nacionalidad como para el país de procedencia.
 *
 * @property int $country_id
 * @property string $country_name
 *
 * @method \Illuminate\Database\Eloquent\Collection usersNationality()
 * @method \Illuminate\Database\Eloquent\Collection usersOrigin()
 */
class Country extends Model
{
    protected $table = 'countries';
    protected $primaryKey = 'country_id';

    protected $fillable = ['country_name'];

    /**
     * Relación: Obtiene los usuarios cuya nacionalidad coincide con este país.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersNationality()
    {
        return $this->hasMany(User::class, 'nationality_id', 'country_id');
    }

    /**
     * Relación: Obtiene los usuarios cuyo país de procedencia es este país.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersOrigin()
    {
        return $this->hasMany(User::class, 'country_origin_id', 'country_id');
    }
}
