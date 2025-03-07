<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProcessComplaintRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        return in_array($user->role->role_name, ['administrador', 'funcionario']);
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'complaint_status_id' => [
                'required',
                Rule::in([2, 3]) // Procesada o desestimada
            ],
            'processing_notes' => [
                'required',
                'string',
                'max:5000'
            ]
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
            'complaint_status_id.required' => 'El estado de la denuncia es requerido',
            'complaint_status_id.in' => 'El estado de la denuncia debe ser: procesada (2) o desestimada (3)',
            'processing_notes.required' => 'Las notas de procesamiento son requeridas',
            'processing_notes.max' => 'Las notas de procesamiento no pueden exceder los 5000 caracteres'
        ];
    }
}
