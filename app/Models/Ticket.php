<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ticket
 *
 * Representa la información de un vuelo (ticket) registrado en el sistema.
 *
 * @property int $ticket_id
 * @property string $flight_number
 * @property string $ticket_number
 * @property string $flight_date
 * @property int $flight_type_id
 * @property int $airline_id
 * @property int $origin_airport_id
 * @property int $destination_airport_id
 * @property int $incident_airport_id
 *
 * @method \Illuminate\Database\Eloquent\Collection complaints()
 */
class Ticket extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'flight_number',
        'ticket_number',
        'flight_date',
        'flight_type_id',
        'airline_id',
        'origin_airport_id',
        'destination_airport_id',
        'incident_airport_id'
    ];

    /**
     * Relación: El tipo de vuelo asociado a este ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function flightType()
    {
        return $this->belongsTo(FlightType::class, 'flight_type_id', 'flight_type_id');
    }

    /**
     * Relación: La aerolínea del ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function airline()
    {
        return $this->belongsTo(Airline::class, 'airline_id', 'airline_id');
    }

    /**
     * Relación: El aeropuerto de origen del vuelo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function originAirport()
    {
        return $this->belongsTo(Airport::class, 'origin_airport_id', 'airport_id');
    }

    /**
     * Relación: El aeropuerto de destino del vuelo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destinationAirport()
    {
        return $this->belongsTo(Airport::class, 'destination_airport_id', 'airport_id');
    }

    /**
     * Relación: El aeropuerto donde ocurrió el incidente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function incidentAirport()
    {
        return $this->belongsTo(Airport::class, 'incident_airport_id', 'airport_id');
    }

    /**
     * Relación: Denuncias asociadas a este ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'ticket_id', 'ticket_id');
    }
}
