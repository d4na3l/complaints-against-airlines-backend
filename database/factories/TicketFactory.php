<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'       => $this->faker->numberBetween(1, 10),
            'flight_number' => $this->faker->numerify('FL#####'),
            'ticket_number' => $this->faker->numerify('TKT-####'),
            'flight_date'   => $this->faker->dateTimeBetween('-1 week', '+1 week')->format('Y-m-d'),
            'flight_type_id' => $this->faker->randomElement([1, 2]),
            'airline_id'    => $this->faker->numberBetween(1, 3),
            'origin_airport_id'      => $this->faker->numberBetween(1, 4),
            'destination_airport_id' => $this->faker->numberBetween(1, 4),
        ];
    }
}
