<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypesSeeder extends Seeder
{
    public function run()
    {
        DB::table('document_types')->insert([
            ['document_type_name' => 'cédula identidad venezolano', 'keyword' => 'civ'],
            ['document_type_name' => 'cédula identidad extranjero', 'keyword' => 'cie'],
            ['document_type_name' => 'pasaporte', 'keyword' => 'pas'],
        ]);
    }
}
