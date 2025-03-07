<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreComplaintRequest extends FormRequest
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
        $user = Auth::user();
        $usingExistingTicket = $this->has('ticket_id') && !$this->has('flight_number');

        // Reglas base para la denuncia
        $rules = [
            'incident_date' => [
                'required',
                'date',
                'before_or_equal:today'
            ],
            'motive_id' => [
                'required',
                'exists:motives,motive_id'
            ],
            'incident_airport_id' => [
                'required',
                'exists:airports,airport_id'
            ],
            'description' => [
                'required',
                'string',
                'max:5000'
            ],
            'files.*' => [
                'nullable',
                'file',
                'max:10240', // 10MB máximo
                'mimes:pdf,jpg,jpeg,png,doc,docx'
            ]
        ];

        // Reglas específicas según el caso (ticket existente o nuevo)
        if ($usingExistingTicket) {
            // Usando ticket existente
            $rules['ticket_id'] = [
                'required',
                'exists:tickets,ticket_id',
                Rule::exists('tickets')->where(function ($query) use ($user) {
                    $query->where('user_id', $user->user_id);
                }),
            ];
        } else {
            // Obtener reglas del StoreTicketRequest
            $ticketRequest = new StoreTicketRequest();
            $rules = array_merge($rules, $ticketRequest->rules());
        }

        return $rules;
    }

    /**
     * Mensajes de error personalizados para validación.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        // Mensajes base para la denuncia
        $messages = [
            'incident_date.required' => 'La fecha del incidente es requerida',
            'incident_date.date' => 'La fecha del incidente debe ser una fecha válida',
            'incident_date.before_or_equal' => 'La fecha del incidente no puede ser posterior a hoy',
            'motive_id.required' => 'El motivo de la denuncia es requerido',
            'motive_id.exists' => 'El motivo seleccionado no es válido',
            'incident_airport_id.required' => 'El aeropuerto donde ocurrió el incidente es requerido',
            'incident_airport_id.exists' => 'El aeropuerto seleccionado no es válido',
            'description.required' => 'La descripción del incidente es requerida',
            'description.max' => 'La descripción no puede exceder los 5000 caracteres',
            'ticket_id.required' => 'El ID del ticket es requerido',
            'ticket_id.exists' => 'El ticket seleccionado no existe o no le pertenece',
        ];

        // Mensajes para archivos
        $fileRequest = new StoreFileRequest();
        $fileMessages = $fileRequest->messages();

        // Mensajes para ticket
        $ticketRequest = new StoreTicketRequest();
        $ticketMessages = $ticketRequest->messages();

        // Unir todos los mensajes
        return array_merge($messages, $fileMessages, $ticketMessages);
    }
}
