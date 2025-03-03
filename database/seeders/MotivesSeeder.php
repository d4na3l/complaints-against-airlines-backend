<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotivesSeeder extends Seeder
{
    public function run()
    {
        DB::table('motives')->insert([
            ['motive' => 'Denegación de embarque'],
            ['motive' => 'Demora en el embarque'],
            ['motive' => 'Cancelación de vuelo'],
            ['motive' => 'Pérdida de conexión'],
            ['motive' => 'Retraso en el equipaje'],
            ['motive' => 'Pérdida, avería o destrucción de equipaje'],
            ['motive' => 'Reembolso de boleto'],
            ['motive' => 'Maltrato'],
            ['motive' => 'No suministrar información sobre condiciones del transporte y otros'],
            ['motive' => 'No suministrar información sobre cambios y cancelación de vuelo'],
        ]);
    }
}
