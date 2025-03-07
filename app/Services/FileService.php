<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Sube un archivo y lo registra en la base de datos.
     *
     * @param UploadedFile $file
     * @param int $complaintId
     * @return File|null
     */
    public function uploadFile(UploadedFile $file, $complaintId)
    {
        try {
            $path = $file->store('complaint_files', 'public');

            $fileModel = new File();
            $fileModel->filename = $file->getClientOriginalName();
            $fileModel->path = $path;
            $fileModel->size = $file->getSize();
            $fileModel->file_type = $file->getClientOriginalExtension();
            $fileModel->complaint_id = $complaintId;
            $fileModel->save();

            return $fileModel;
        } catch (\Exception $e) {
            Log::error('Error al guardar archivo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Sube múltiples archivos asociados a una denuncia.
     *
     * @param array $files
     * @param int $complaintId
     * @return array
     */
    public function uploadFiles(array $files, $complaintId)
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            $uploadedFile = $this->uploadFile($file, $complaintId);
            if ($uploadedFile) {
                $uploadedFiles[] = $uploadedFile;
            }
        }

        return $uploadedFiles;
    }
}
