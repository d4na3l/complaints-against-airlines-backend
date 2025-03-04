<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirlinesSeeder extends Seeder
{
    public function run()
    {
        DB::table('airlines')->insert([
            ['airline_name' => 'Aerolínea Ficticia 1'],
            ['airline_name' => 'Aerolínea Ficticia 2'],
            ['airline_name' => 'Aerolínea Ficticia 3'],
        ]);
    }
}
