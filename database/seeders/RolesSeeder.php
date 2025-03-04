<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['role_name' => 'administrado'],
            ['role_name' => 'administrador'],
            ['role_name' => 'funcionario'],
        ]);
    }
}
