<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlightTypesSeeder extends Seeder
{
    public function run()
    {
        DB::table('flight_types')->insert([
            ['flight_type' => 'nacional'],
            ['flight_type' => 'internacional'],
        ]);
    }
}
