<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ComplaintController extends Controller
{
    /**
     * Listar denuncias para administrado
     */
    public function listComplaints(Request $request)
    {
        $user = Auth::user();

        // Si es administrado, solo ve sus propias denuncias
        if ($user->role->role_name === 'administrado') {
            $complaints = Complaint::where('user_id', $user->user_id)
                ->with(['complaintStatus', 'motive', 'ticket.airline', 'ticket.originAirport', 'ticket.destinationAirport'])
                ->orderBy('registration_date', 'desc')
                ->get();
        }
        // Si es administrador o funcionario, ve todas las denuncias
        elseif (in_array($user->role->role_name, ['administrador', 'funcionario'])) {
            $complaints = Complaint::with([
                'user',
                'complaintStatus',
                'motive',
                'ticket.airline',
                'ticket.originAirport',
                'ticket.destinationAirport',
                'processedBy'
            ])->orderBy('registration_date', 'desc')->get();
        } else {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json($complaints);
    }

    /**
     * Registrar nueva denuncia
     */
    public function storeComplaint(Request $request)
    {
        $user = Auth::user();

        // Validaciones para el registro de denuncia
        $validator = Validator::make($request->all(), [
            'incident_date' => [
                'required',
                'date',
                'before_or_equal:today'
            ],
            'ticket_id' => [
                'required',
                'exists:tickets,ticket_id'
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
                'file',
                'max:10240', // 10MB máximo
                'mimes:pdf,jpg,jpeg,png,doc,docx'
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Crear denuncia
        $complaint = new Complaint();
        $complaint->registration_date = now();
        $complaint->incident_date = $request->incident_date;
        $complaint->description = $request->description;
        $complaint->motive_id = $request->motive_id;
        $complaint->incident_airport_id = $request->incident_airport_id;
        $complaint->ticket_id = $request->ticket_id;
        $complaint->user_id = $user->user_id;
        $complaint->complaint_status_id = 1; // Estado inicial: en espera
        $complaint->save();

        // Manejar archivos adjuntos
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $uploadedFile) {
                $path = $uploadedFile->store('complaint_files', 'public');

                $file = new File();
                $file->filename = $uploadedFile->getClientOriginalName();
                $file->path = $path;
                $file->size = $uploadedFile->getSize();
                $file->file_type = $uploadedFile->getClientOriginalExtension();
                $file->complaint_id = $complaint->complaint_id;
                $file->save();
            }
        }

        return response()->json([
            'message' => 'Denuncia registrada exitosamente',
            'complaint_id' => $complaint->complaint_id
        ], 201);
    }

    /**
     * Procesar denuncia (para administrador y funcionario)
     */
    public function processComplaint(Request $request, $complaintId)
    {
        $user = Auth::user();

        // Solo administradores y funcionarios pueden procesar
        if (!in_array($user->role->role_name, ['administrador', 'funcionario'])) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'complaint_status_id' => [
                'required',
                Rule::in([2, 3]) // Procesada o desestimada
            ],
            'processing_notes' => [
                'required',
                'string',
                'max:5000'
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $complaint = Complaint::findOrFail($complaintId);

        $complaint->complaint_status_id = $request->complaint_status_id;
        $complaint->processing_notes = $request->processing_notes;
        $complaint->processed_by = $user->user_id;
        $complaint->save();

        return response()->json([
            'message' => 'Denuncia procesada exitosamente',
            'complaint_id' => $complaint->complaint_id
        ]);
    }
}
