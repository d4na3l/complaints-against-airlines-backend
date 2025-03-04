<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirportsSeeder extends Seeder
{
    public function run()
    {
        DB::table('airports')->insert([
            ['airport_name' => 'Aeropuerto Ficticio A'],
            ['airport_name' => 'Aeropuerto Ficticio B'],
            ['airport_name' => 'Aeropuerto Ficticio C'],
        ]);
    }
}
