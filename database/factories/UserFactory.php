<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;
    
    public function definition()
    {
        return [
            'first_name'        => $this->faker->firstName,
            'last_name'         => $this->faker->lastName,
            'birthdate'         => $this->faker->date('Y-m-d', '2002-12-31'),
            'password'          => Hash::make('password123'), // Se usará la misma contraseña para todos
            'document'          => $this->faker->numerify('########'),
            'document_type_id'  => 1, // Se puede sobreescribir en el seeder si es necesario
            'email'             => $this->faker->unique()->safeEmail, // Se sobrescribirá en el seeder
            'phone'             => $this->faker->phoneNumber,
            'local_phone'       => $this->faker->phoneNumber,
            'profession'        => $this->faker->jobTitle,
            'role_id'           => 1, // Valor por defecto, se sobreescribe en el seeder
            'nationality_id'    => 243, // Se asume que el país ya existe en la tabla countries
            'country_origin_id' => 243, // Mismo valor que nationality_id, para este ejemplo
            'domicile_address'  => $this->faker->address,
            'additional_address'=> $this->faker->address,
        ];
    }
}
