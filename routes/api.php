<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;

/*
|--------------------------------------------------------------------------
| API Routes - Versión 1 (v1)
|--------------------------------------------------------------------------
|
| Estas rutas conforman la API para la gestión de denuncias aeroportuarias.
| Están diseñadas para ser consumidas por la aplicación Angular 19.
|
| Se aplican dos tipos principales de middleware:
|   - 'auth:sanctum': Asegura que el usuario esté autenticado.
|   - 'role:[tipo_ruta]': Middleware personalizado que valida el rol del usuario
|                         según las siguientes reglas del negocio:
|
|   * Usuarios con rol "administrado":
|       - Solo pueden registrar denuncias y consultar sus propias denuncias.
|
|   * Usuarios con rol "administrador":
|       - Pueden listar todos los usuarios y todas las denuncias.
|       - Pueden procesar denuncias.
|
|   * Usuarios con rol "funcionario":
|       - Pueden listar todas las denuncias.
|       - Pueden procesar denuncias.
|
| Para facilitar la gestión, se definen tres configuraciones de rutas en el middleware
| 'role':
|   - "administrado_routes": Para endpoints exclusivos de usuarios administrados.
|   - "admin_routes": Para endpoints exclusivos de administradores.
|   - "shared_routes": Para endpoints compartidos entre administradores y funcionarios.
|
*/

Route::get('/generate-test-token', function () {
    // Solo para entorno de desarrollo
    if (app()->environment('production')) {
        abort(403);
    }

    $user = \App\Models\User::find(1); // Obtener el usuario por ID
    $token = $user->createToken('test-token')->plainTextToken;

    return ['token' => $token];
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    /*
     * [Listado de Denuncias]
     *
     * Endpoint: GET /api/v1/denuncias
     *
     * - Si el usuario autenticado tiene rol "administrado", solo se listarán sus denuncias.
     * - Si el usuario tiene rol "administrador" o "funcionario", se listarán todas las denuncias.
     *
     * No se aplica un middleware de rol específico ya que el controlador distingue
     * el listado según el rol del usuario.
     */
    Route::get('/denuncias', [ComplaintController::class, 'listComplaints'])
        ->name('denuncias.list');

    /*
     * [Detalle de Denuncia]
     *
     * Endpoint: GET /api/v1/denuncias/{complaintId}
     *
     * - Si el usuario autenticado tiene rol "administrado", solo podrá ver una denuncia si le pertenece.
     * - Si el usuario tiene rol "administrador" o "funcionario", podrá ver cualquier denuncia.
     *
     * No se aplica un middleware de rol específico ya que el controlador se encarga
     * de verificar los permisos según el rol del usuario.
     */
    Route::get('/denuncias/{complaintId}', [ComplaintController::class, 'showComplaint'])
        ->name('denuncias.show');

    /*
     * [Registro de Denuncia]
     *
     * Endpoint: POST /api/v1/denuncias
     *
     * Permite a un usuario con rol "administrado" registrar una nueva denuncia.
     * Se validan datos como la fecha del hecho, la existencia del ticket, motivo, descripción,
     * y se gestionan los archivos adjuntos.
     *
     * Se restringe con el middleware 'role:administrado_routes' para asegurar que solo
     * usuarios administrados puedan acceder a esta funcionalidad.
     */
    Route::post('/denuncias', [ComplaintController::class, 'storeComplaint'])
        ->middleware('role:administrado_routes')
        ->name('denuncias.store');

    /*
     * [Procesamiento de Denuncias]
     *
     * Endpoint: PUT /api/v1/denuncias/{complaintId}/procesar
     *
     * Permite a usuarios con rol "administrador" o "funcionario" actualizar el estado de una denuncia,
     * agregando notas de procesamiento (por ejemplo, razón de desestimación o pasos siguientes).
     *
     * Se aplica el middleware 'role:shared_routes' para limitar el acceso a administradores y funcionarios.
     */
    Route::put('/denuncias/{complaintId}/procesar', [ComplaintController::class, 'processComplaint'])
        ->middleware('role:shared_routes')
        ->name('denuncias.process');
});
