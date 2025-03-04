<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Airport
 *
 * Representa un aeropuerto.
 *
 * @property int $airport_id
 * @property string $airport_name
 *
 * @method \Illuminate\Database\Eloquent\Collection originTickets()
 * @method \Illuminate\Database\Eloquent\Collection destinationTickets()
 * @method \Illuminate\Database\Eloquent\Collection incidentTickets()
 * @method \Illuminate\Database\Eloquent\Collection airlines()
 */
class Airport extends Model
{
    protected $table = 'airports';
    protected $primaryKey = 'airport_id';

    protected $fillable = ['airport_name'];

    /**
     * Relación: Tickets donde este aeropuerto es el de origen.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function originTickets()
    {
        return $this->hasMany(Ticket::class, 'origin_airport_id', 'airport_id');
    }

    /**
     * Relación: Tickets donde este aeropuerto es el de destino.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function destinationTickets()
    {
        return $this->hasMany(Ticket::class, 'destination_airport_id', 'airport_id');
    }

    /**
     * Relación: Tickets donde este aeropuerto es el de incidente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incidentTickets()
    {
        return $this->hasMany(Ticket::class, 'incident_airport_id', 'airport_id');
    }

    /**
     * Relación: Una relación many-to-many con aerolíneas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function airlines()
    {
        return $this->belongsToMany(Airline::class, 'airline_airports', 'airport_id', 'airline_id');
    }
}
