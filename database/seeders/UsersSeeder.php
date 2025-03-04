<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Recuperar los roles
        $administradoRole = Role::where('role_name', 'administrado')->first();
        $administradorRole = Role::where('role_name', 'administrador')->first();
        $funcionarioRole = Role::where('role_name', 'funcionario')->first();

        // Verificar que existan los roles
        if (!$administradoRole || !$administradorRole || !$funcionarioRole) {
            $this->command->error('No se encontraron los roles necesarios. Por favor, ejecuta primero el seeder de roles.');
            return;
        }

        // Definir la relación roleName => role_id
        $roles = [
            'administrado'   => $administradoRole->role_id,
            'administrador'  => $administradorRole->role_id,
            'funcionario'    => $funcionarioRole->role_id,
        ];

        // Crear 3 usuarios para cada rol
        foreach ($roles as $roleName => $roleId) {
            for ($i = 1; $i <= 3; $i++) {
                User::factory()->create([
                    'role_id' => $roleId,
                    // Email con la sintaxis: {role}{número}.test@gmail.com
                    'email'   => "{$roleName}{$i}.test@gmail.com",
                    // Se asegura que la contraseña sea 'password123'
                    'password'=> bcrypt('password123'),
                ]);
            }
        }
    }
}
