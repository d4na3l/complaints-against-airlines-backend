<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintStatusSeeder extends Seeder
{
    public function run()
    {
        DB::table('complaint_status')->insert([
            ['status_name' => 'en espera'],
            ['status_name' => 'procesada'],
            ['status_name' => 'desestimada'],
        ]);
    }
}
