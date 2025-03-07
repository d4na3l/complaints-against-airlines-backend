<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTicketRequest extends FormRequest
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
            'flight_number' => ['required', 'string', 'max:20'],
            'ticket_number' => ['required', 'string', 'max:50'],
            'flight_date' => ['required', 'date'],
            'flight_type_id' => ['required', 'exists:flight_types,flight_type_id'],
            'airline_id' => ['required', 'exists:airlines,airline_id'],
            'origin_airport_id' => ['required', 'exists:airports,airport_id'],
            'destination_airport_id' => ['required', 'exists:airports,airport_id'],
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
            'flight_number.required' => 'El número de vuelo es requerido',
            'flight_number.max' => 'El número de vuelo no puede exceder los 20 caracteres',
            'ticket_number.required' => 'El número de ticket es requerido',
            'ticket_number.max' => 'El número de ticket no puede exceder los 50 caracteres',
            'flight_date.required' => 'La fecha del vuelo es requerida',
            'flight_date.date' => 'La fecha del vuelo debe ser una fecha válida',
            'flight_type_id.required' => 'El tipo de vuelo es requerido',
            'flight_type_id.exists' => 'El tipo de vuelo seleccionado no es válido',
            'airline_id.required' => 'La aerolínea es requerida',
            'airline_id.exists' => 'La aerolínea seleccionada no es válida',
            'origin_airport_id.required' => 'El aeropuerto de origen es requerido',
            'origin_airport_id.exists' => 'El aeropuerto de origen seleccionado no es válido',
            'destination_airport_id.required' => 'El aeropuerto de destino es requerido',
            'destination_airport_id.exists' => 'El aeropuerto de destino seleccionado no es válido',
        ];
    }
}
