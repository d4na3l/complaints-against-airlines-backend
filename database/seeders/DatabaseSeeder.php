<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Llamar a los seeders de datos referenciales
        $this->call([
            RolesSeeder::class,
            DocumentTypesSeeder::class,
            CountriesSeeder::class, // se poblará con nerdsnipe/laravel-countries
            ComplaintStatusSeeder::class,
            FlightTypesSeeder::class,
            MotivesSeeder::class,
            AirlinesSeeder::class,
            AirportsSeeder::class,
            AirlineAirportsSeeder::class,
            // Otros seeders necesarios...
            UsersSeeder::class, // Seeder para crear usuarios de prueba
            UserComplaintsSeeder::class,
            FilesSeeder::class,
        ]);
    }
}
