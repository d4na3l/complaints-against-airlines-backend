<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFileRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->role->role_name === 'administrado';
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'files' => ['required', 'array', 'min:1'],
            'files.*' => [
                'required',
                'file',
                'max:10240', // 10MB máximo
                'mimes:pdf,jpg,jpeg,png,doc,docx'
            ],
            'complaint_id' => ['required', 'exists:complaints,complaint_id']
        ];
    }

    /**
     * Mensajes de error personalizados para validación.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'files.required' => 'Debe seleccionar al menos un archivo',
            'files.array' => 'El formato de los archivos es inválido',
            'files.min' => 'Debe seleccionar al menos un archivo',
            'files.*.required' => 'El archivo es requerido',
            'files.*.file' => 'El elemento subido debe ser un archivo',
            'files.*.max' => 'El archivo no puede superar los 10MB',
            'files.*.mimes' => 'Solo se permiten archivos de tipo: pdf, jpg, jpeg, png, doc, docx',
            'complaint_id.required' => 'El ID de la denuncia es requerido',
            'complaint_id.exists' => 'La denuncia seleccionada no existe'
        ];
    }
}
