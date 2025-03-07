<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    /**
     * Crea un nuevo ticket a partir de los datos proporcionados.
     *
     * @param array $data
     * @return Ticket
     */
    public function createTicket(array $data)
    {
        $user = Auth::user();

        $ticket = new Ticket();
        $ticket->flight_number = $data['flight_number'];
        $ticket->ticket_number = $data['ticket_number'];
        $ticket->flight_date = $data['flight_date'];
        $ticket->flight_type_id = $data['flight_type_id'];
        $ticket->airline_id = $data['airline_id'];
        $ticket->origin_airport_id = $data['origin_airport_id'];
        $ticket->destination_airport_id = $data['destination_airport_id'];
        $ticket->user_id = $user->user_id;
        $ticket->save();

        return $ticket;
    }

    /**
     * Verifica si un ticket pertenece al usuario actual.
     *
     * @param int $ticketId
     * @return Ticket|null
     */
    public function getUserTicket($ticketId)
    {
        $user = Auth::user();

        return Ticket::where('ticket_id', $ticketId)
            ->where('user_id', $user->user_id)
            ->first();
    }
}
