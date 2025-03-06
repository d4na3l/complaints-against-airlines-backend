<?php

namespace Database\Factories;

use App\Models\Complaint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
    protected $model = Complaint::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generamos fechas aleatorias en el último mes
        $registrationDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $incidentDate = $this->faker->dateTimeBetween('-1 month', 'now');

        return [
            'registration_date'   => $registrationDate,
            'incident_date'       => $incidentDate->format('Y-m-d'),
            'description'         => $this->faker->paragraph,
            'motive_id'           => $this->faker->numberBetween(1, 5), // Ajusta el rango según la cantidad de motivos existentes
            'ticket_id'           => $this->faker->numberBetween(1, 10), // Este valor se sobreescribirá en el seeder
            'user_id'             => $this->faker->numberBetween(1, 10), // Este valor se sobreescribirá en el seeder
            'incident_airport_id' => $this->faker->numberBetween(1, 3), // Ajusta el rango según la cantidad de aeropuertos existentes
            'complaint_status_id' => $this->faker->numberBetween(1, 3), // 1: en espera, 2: procesada, 3: desestimada
            // 'processing_notes' puede definirse si se requiere en estados posteriores
        ];
    }
}
