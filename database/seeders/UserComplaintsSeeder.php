<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Complaint;
use App\Models\Motive;
use App\Models\ComplaintStatus;
use App\Models\Airport; // Asegúrate de importar el modelo Airport
use Illuminate\Database\Seeder;

class UserComplaintsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos necesarios
        $motive = Motive::first();
        $status = ComplaintStatus::first();

        if (!$motive || !$status) {
            $this->command->error('No se encontraron datos necesarios para crear tickets y denuncias.');
            return;
        }

        // Crear un ticket para vuelo nacional (sin incident_airport_id)
        $ticket = Ticket::factory()->create([
            'flight_number' => 'NAT-' . rand(10000, 99999),
            'ticket_number' => 'TKT-' . rand(1000, 9999),
            'flight_date' => now()->subDay()->toDateString(),
            'flight_type_id' => 1,
            'airline_id' => 1,
            'origin_airport_id' => 1,
            'destination_airport_id' => 2,
            'user_id' => 1,
            // 'incident_airport_id' => 1, // Se elimina esta línea
        ]);

        // Crear dos denuncias para el usuario 1 (con diferentes tickets)
        Complaint::factory()->create([
            'registration_date' => now(),
            'incident_date' => now()->subDay()->toDateString(),
            'description' => 'Denuncia 1 para usuario 1',
            'motive_id' => $motive->motive_id,
            'ticket_id' => $ticket->ticket_id,
            'user_id' => 1,
            'complaint_status_id' => $status->complaint_status_id,
            'incident_airport_id' => 1, // Se agrega incident_airport_id
        ]);

        // Crear un segundo ticket para usuario 1 (sin incident_airport_id)
        $ticket2 = Ticket::factory()->create([
            'flight_number' => 'INT-' . rand(10000, 99999),
            'ticket_number' => 'TKT-' . rand(1000, 9999),
            'flight_date' => now()->subDays(2)->toDateString(),
            'flight_type_id' => 2,
            'airline_id' => 2,
            'origin_airport_id' => 2,
            'destination_airport_id' => 3,
            'user_id' => 1,
            // 'incident_airport_id' => 2, // Se elimina esta línea
        ]);

        Complaint::factory()->create([
            'registration_date' => now(),
            'incident_date' => now()->subDays(2)->toDateString(),
            'description' => 'Denuncia 2 para usuario 1',
            'motive_id' => $motive->motive_id,
            'ticket_id' => $ticket2->ticket_id,
            'user_id' => 1,
            'complaint_status_id' => $status->complaint_status_id,
            'incident_airport_id' => 1, // Se agrega incident_airport_id
        ]);

        // Crear un segundo ticket para usuario 1 (sin incident_airport_id)
        $ticket3 = Ticket::factory()->create([
            'flight_number' => 'INT-' . rand(10000, 99999),
            'ticket_number' => 'TKT-' . rand(1000, 9999),
            'flight_date' => now()->subDays(2)->toDateString(),
            'flight_type_id' => 2,
            'airline_id' => 2,
            'origin_airport_id' => 2,
            'destination_airport_id' => 3,
            'user_id' => 2,
            // 'incident_airport_id' => 2, // Se elimina esta línea
        ]);

        // Crear dos denuncias para el usuario 2 con el MISMO ticket
        Complaint::factory()->create([
            'registration_date' => now(),
            'incident_date' => now()->subDays(3)->toDateString(),
            'description' => 'Primera denuncia del usuario 2 con el mismo ticket',
            'motive_id' => $motive->motive_id,
            'ticket_id' => $ticket3->ticket_id,
            'user_id' => 2,
            'complaint_status_id' => $status->complaint_status_id,
            'incident_airport_id' => 1, // Se agrega incident_airport_id
        ]);

        Complaint::factory()->create([
            'registration_date' => now(),
            'incident_date' => now()->subDays(3)->toDateString(),
            'description' => 'Segunda denuncia del usuario 2 con el mismo ticket',
            'motive_id' => $motive->motive_id,
            'ticket_id' => $ticket3->ticket_id,
            'user_id' => 2,
            'complaint_status_id' => $status->complaint_status_id,
            'incident_airport_id' => 1, // Se agrega incident_airport_id
        ]);

        $this->command->info('Se generaron denuncias correctamente.');
    }
}
