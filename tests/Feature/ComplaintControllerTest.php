<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Complaint;
use \App\Models\Ticket;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplaintControllerTest extends TestCase
{

    /**
     * Configuración inicial antes de cada prueba.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Simular almacenamiento de archivos
        Storage::fake('public');

        // Ejecutar seeders para poblar la base de datos
        // $this->seed(); // Esto ejecuta DatabaseSeeder y carga todos los datos
    }

    /**
     * Verifica que un usuario no autenticado reciba 401 al intentar listar denuncias.
     */
    public function test_unauthenticated_user_cannot_list_complaints()
    {
        $response = $this->getJson('/api/v1/denuncias');
        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * Verifica que un usuario con rol "administrado" solo vea sus propias denuncias.
     */
    public function test_administrado_sees_only_own_complaints()
    {
        $user = User::where('role_id', 1)->first(); // Obtener el primer usuario "administrado"

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/denuncias');
        $response->assertStatus(200);

        $complaints = $response->json();
        $userComplaintIds = Complaint::where('user_id', $user->user_id)->pluck('complaint_id')->toArray();

        $this->assertCount(count($userComplaintIds), $complaints);
        foreach ($complaints as $complaint) {
            $this->assertContains($complaint['complaint_id'], $userComplaintIds);
        }
    }

    /**
     * Verifica que un usuario con rol "administrador" pueda listar TODAS las denuncias.
     */
    public function test_administrador_sees_all_complaints()
    {
        $admin = User::where('role_id', 2)->first(); // Obtener el primer administrador

        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/v1/denuncias');
        $response->assertStatus(200);

        $complaints = $response->json();
        $totalComplaints = Complaint::count();

        $this->assertCount($totalComplaints, $complaints);
    }

    /**
     * Verifica que un usuario con rol "funcionario" pueda listar TODAS las denuncias.
     */
    public function test_funcionario_sees_all_complaints()
    {
        $funcionario = User::where('role_id', 3)->first(); // Obtener el primer funcionario

        $response = $this->actingAs($funcionario, 'sanctum')->getJson('/api/v1/denuncias');
        $response->assertStatus(200);

        $complaints = $response->json();
        $totalComplaints = Complaint::count();

        $this->assertCount($totalComplaints, $complaints);
    }

    /**
     * Verifica que un usuario con rol "administrado" pueda registrar una denuncia correctamente.
     */
    public function test_store_complaint_successful()
    {
        $user = User::where('role_id', 1)->first();
        $ticket = $user->tickets()->first(); // Obtener un ticket de ese usuario
        if (!$ticket) {
            $ticket = Ticket::factory()->create(['user_id' => $user->user_id]);
        }
        $motive = \App\Models\Motive::first();

        $data = [
            'incident_date' => now()->toDateString(),
            'ticket_id'     => $ticket->ticket_id,
            'motive_id'     => $motive->motive_id,
            'description'   => 'Nueva denuncia de prueba',
            'incident_airport_id' => 3,
            'files'         => [
                UploadedFile::fake()->create('documento.pdf', 500, 'application/pdf')
            ]
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/denuncias', $data);
        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'complaint_id']);

        $complaintId = $response->json('complaint_id');
        $this->assertDatabaseHas('complaints', [
            'complaint_id' => $complaintId,
            'user_id' => $user->user_id
        ]);
    }

    /**
     * Verifica que un usuario con rol "administrador" pueda procesar denuncias exitosamente.
     */
    public function test_admin_can_process_complaints()
    {
        $admin = User::where('role_id', 2)->first();
        $complaint = Complaint::first();

        $data = [
            'complaint_status_id' => 2, // Procesada
            'processing_notes' => 'Denuncia procesada exitosamente'
        ];

        $response = $this->actingAs($admin, 'sanctum')->putJson("/api/v1/denuncias/{$complaint->complaint_id}/procesar", $data);
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'complaint_id']);

        $this->assertDatabaseHas('complaints', [
            'complaint_id' => $complaint->complaint_id,
            'complaint_status_id' => 2,
            'processing_notes' => 'Denuncia procesada exitosamente',
            'processed_by' => $admin->user_id
        ]);
    }

    /**
     * Verifica que un usuario con rol "funcionario" pueda procesar denuncias exitosamente.
     */
    public function test_funcionario_can_process_complaints()
    {
        $funcionario = User::where('role_id', 3)->first();
        $complaint = Complaint::first();

        $data = [
            'complaint_status_id' => 3, // Desestimada
            'processing_notes' => 'Denuncia desestimada por falta de pruebas'
        ];

        $response = $this->actingAs($funcionario, 'sanctum')->putJson("/api/v1/denuncias/{$complaint->complaint_id}/procesar", $data);
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'complaint_id']);

        $this->assertDatabaseHas('complaints', [
            'complaint_id' => $complaint->complaint_id,
            'complaint_status_id' => 3,
            'processing_notes' => 'Denuncia desestimada por falta de pruebas',
            'processed_by' => $funcionario->user_id
        ]);
    }
}
