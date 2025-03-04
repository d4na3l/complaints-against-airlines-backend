<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FlightType
 *
 * Define los tipos de vuelos: por ejemplo, nacional o internacional.
 *
 * @property int $flight_type_id
 * @property string $flight_type
 *
 * @method \Illuminate\Database\Eloquent\Collection tickets()
 */
class FlightType extends Model
{
    protected $table = 'flight_types';
    protected $primaryKey = 'flight_type_id';

    protected $fillable = ['flight_type'];

    /**
     * Relación: Un tipo de vuelo se asocia a muchos tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'flight_type_id', 'flight_type_id');
    }
}
