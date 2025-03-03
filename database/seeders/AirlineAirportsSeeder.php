<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirlineAirportsSeeder extends Seeder
{
    public function run()
    {
        // Supongamos que la aerolínea con id 1 opera en los aeropuertos 1 y 2
        DB::table('airline_airports')->insert([
            ['airline_id' => 1, 'airport_id' => 1],
            ['airline_id' => 1, 'airport_id' => 2],
            // La aerolínea 2 opera en el aeropuerto 2 y 3
            ['airline_id' => 2, 'airport_id' => 2],
            ['airline_id' => 2, 'airport_id' => 3],
        ]);
    }
}
