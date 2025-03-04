<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Airline
 *
 * Representa una aerolínea.
 *
 * @property int $airline_id
 * @property string $airline_name
 *
 * @method \Illuminate\Database\Eloquent\Collection tickets()
 * @method \Illuminate\Database\Eloquent\Collection airports()
 */
class Airline extends Model
{
    protected $table = 'airlines';
    protected $primaryKey = 'airline_id';

    protected $fillable = ['airline_name'];

    /**
     * Relación: Una aerolínea puede tener muchos tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'airline_id', 'airline_id');
    }

    /**
     * Relación: Una aerolínea opera en varios aeropuertos (relación many-to-many).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function airports()
    {
        return $this->belongsToMany(Airport::class, 'airline_airports', 'airline_id', 'airport_id');
    }
}
