<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\File;
use App\Models\Complaint; // Asegúrate de importar el modelo Complaint
use Illuminate\Support\Facades\Storage;

class FilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $complaint = Complaint::first(); // Obtiene la primera denuncia o null

        if (!$complaint) {
            // Crea una denuncia dummy si no existe (opcional)
            $complaint = Complaint::factory()->create();
        }

        $numFiles = 5; // Número de archivos a crear

        for ($i = 1; $i <= $numFiles; $i++) {
            $filename = fake()->word . '.' . fake()->fileExtension;
            $path = 'complaint_files/' . $filename;
            $size = fake()->numberBetween(1024, 1024000); // Tamaño aleatorio entre 1KB y 1MB
            $fileType = pathinfo($filename, PATHINFO_EXTENSION);

            File::create([
                'filename' => $filename,
                'path' => $path,
                'size' => $size,
                'file_type' => $fileType,
                'complaint_id' => $complaint->complaint_id,
            ]);
        }

        $this->command->info("Se han creado $numFiles archivos de ejemplo");
    }
}
