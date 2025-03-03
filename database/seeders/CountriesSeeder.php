<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ruta al archivo JSON (ajusta la ruta si es necesario)
        $jsonPath = 'data/countries.json';

        $json = Storage::get($jsonPath);
        $countries = json_decode($json, true);

        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'country_name' => $country['name'],
            ]);
        }
    }
}