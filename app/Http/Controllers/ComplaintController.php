<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessComplaintRequest;
use App\Http\Requests\StoreComplaintRequest;
use App\Models\Complaint;
use App\Services\FileService;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class ComplaintController extends Controller
{
    protected $ticketService;
    protected $fileService;

    /**
     * Constructor del controlador.
     *
     * @param TicketService $ticketService
     * @param FileService $fileService
     */
    public function __construct(TicketService $ticketService, FileService $fileService)
    {
        $this->ticketService = $ticketService;
        $this->fileService = $fileService;
    }

    /**
     * Listar denuncias para el usuario autenticado
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * Mostrar detalles de una denuncia específica
     * 
     * @param int $complaintId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showComplaint($complaintId)
    {
        $user = Auth::user();

        try {
            // Query base con todas las relaciones necesarias
            $query = Complaint::with([
                'complaintStatus',
                'motive',
                'ticket.airline',
                'ticket.originAirport',
                'ticket.destinationAirport',
                'ticket.incidentAirport',
                'user',
                'processedBy',
                'files'
            ]);

            // Filtrar según el rol del usuario
            if ($user->role->role_name === 'administrado') {
                // Los administrados solo pueden ver sus propias denuncias
                $complaint = $query->where('user_id', $user->user_id)
                    ->where('complaint_id', $complaintId)
                    ->firstOrFail();
            } elseif (in_array($user->role->role_name, ['administrador', 'funcionario'])) {
                // Los administradores y funcionarios pueden ver cualquier denuncia
                $complaint = $query->where('complaint_id', $complaintId)
                    ->firstOrFail();
            } else {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            return response()->json($complaint);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Denuncia no encontrada',
                'message' => 'La denuncia solicitada no existe o no tienes permisos para acceder a ella'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error al obtener denuncia: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error al obtener la denuncia',
                'message' => env('APP_DEBUG') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Registrar nueva denuncia con creación o verificación de ticket
     * 
     * @param StoreComplaintRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeComplaint(StoreComplaintRequest $request)
    {
        // La validación ya se manejó en el Form Request
        $user = Auth::user();
        $usingExistingTicket = $request->has('ticket_id') && !$request->has('flight_number');

        // Iniciar transacción para asegurar que ambos (ticket y denuncia) se creen o ninguno
        DB::beginTransaction();

        try {
            $ticketId = null;

            // Crear ticket nuevo si es necesario o verificar el existente
            if (!$usingExistingTicket) {
                $ticket = $this->ticketService->createTicket($request->all());
                $ticketId = $ticket->ticket_id;
            } else {
                // El Form Request ya validó que el ticket pertenece al usuario
                $ticketId = $request->ticket_id;
            }

            // Crear denuncia
            $complaint = new Complaint();
            $complaint->registration_date = now();
            $complaint->incident_date = $request->incident_date;
            $complaint->description = $request->description;
            $complaint->motive_id = $request->motive_id;
            $complaint->incident_airport_id = $request->incident_airport_id;
            $complaint->ticket_id = $ticketId;
            $complaint->user_id = $user->user_id;
            $complaint->complaint_status_id = 1; // Estado inicial: en espera
            $complaint->save();

            // Manejar archivos adjuntos
            if ($request->hasFile('files')) {
                $this->fileService->uploadFiles($request->file('files'), $complaint->complaint_id);
            }

            DB::commit();

            return response()->json([
                'message' => 'Denuncia registrada exitosamente',
                'complaint_id' => $complaint->complaint_id,
                'ticket_id' => $ticketId,
                'is_new_ticket' => !$usingExistingTicket
            ], 201);
        } catch (QueryException $qe) {
            DB::rollBack();
            Log::error('Error de base de datos: ' . $qe->getMessage());

            return response()->json([
                'error' => 'Error en la base de datos al registrar la denuncia',
                'message' => env('APP_DEBUG') ? $qe->getMessage() : 'Error interno del servidor'
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error general: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error al registrar la denuncia',
                'message' => env('APP_DEBUG') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Procesar denuncia (para administrador y funcionario)
     * 
     * @param ProcessComplaintRequest $request
     * @param int $complaintId
     * @return \Illuminate\Http\JsonResponse
     */
    public function processComplaint(ProcessComplaintRequest $request, $complaintId)
    {
        // La validación ya se manejó en el Form Request
        $user = Auth::user();

        try {
            $complaint = Complaint::findOrFail($complaintId);

            $complaint->complaint_status_id = $request->complaint_status_id;
            $complaint->processing_notes = $request->processing_notes;
            $complaint->processed_by = $user->user_id;
            $complaint->save();

            return response()->json([
                'message' => 'Denuncia procesada exitosamente',
                'complaint_id' => $complaint->complaint_id
            ]);
        } catch (\Exception $e) {
            Log::error('Error al procesar denuncia: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error al procesar la denuncia',
                'message' => env('APP_DEBUG') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }
}
