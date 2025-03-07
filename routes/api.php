<?php

use App\Http\Controllers\ReferenceDataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Versión 1 (v1)
|--------------------------------------------------------------------------
|
| Estas rutas conforman la API para la gestión de denuncias aeroportuarias.
| Están diseñadas para ser consumidas por la aplicación Angular 19.
|
| Se implementa una estructura de autorización basada en roles utilizando
| Laravel 12, que ofrece una sintaxis más limpia para definir grupos de rutas
| y middleware.
|
| Roles y permisos:
|   * "administrado":
|       - Registrar denuncias
|       - Consultar sus propias denuncias
|
|   * "administrador":
|       - Gestionar usuarios (listar)
|       - Acceder a todas las denuncias
|       - Procesar denuncias
|       - Administrar datos de referencia
|
|   * "funcionario":
|       - Acceder a todas las denuncias
|       - Procesar denuncias
|
*/

// Ruta de prueba para generar token (solo en entorno de desarrollo)
if (app()->environment('local', 'dev', 'test')) {
    Route::get('/generate-test-token', function () {
        $tokens = []; // Array para almacenar los tokens generados

        // Generar token para usuario "administrado" (ID 1)
        $userAdministrado = \App\Models\User::find(1);
        if ($userAdministrado) { // Verifica si el usuario existe
            $tokens['administrado'] = $userAdministrado->createToken('token-administrado')->plainTextToken;
        }

        // Generar token para usuario "administrador" (ID 4)
        $userAdministrador = \App\Models\User::find(4);
        if ($userAdministrador) { // Verifica si el usuario existe
            $tokens['administrador'] = $userAdministrador->createToken('token-administrador')->plainTextToken;
        }

        // Generar token para usuario "funcionario" (ID 7)
        $userFuncionario = \App\Models\User::find(7);
        if ($userFuncionario) { // Verifica si el usuario existe
            $tokens['funcionario'] = $userFuncionario->createToken('token-funcionario')->plainTextToken;
        }

        return [$tokens]; // Devuelve todos los tokens en un array asociativo
    });
}

// Rutas públicas de autenticación
Route::prefix('v1/auth')->group(function () {
    /*
     * [Login de Usuario]
     * 
     * Endpoint: POST /api/v1/auth/login
     * 
     * Autentica a un usuario y devuelve un token de acceso.
     * No requiere autenticación previa.
     */
    Route::post('/login', [AuthController::class, 'loginUser'])
        ->name('auth.login');

    /*
     * [Registro de Usuario]
     * 
     * Endpoint: POST /api/v1/auth/register
     * 
     * Registra un nuevo usuario con rol "administrado".
     * No permite registrar usuarios con otros roles por seguridad.
     */
    Route::post('/register', [AuthController::class, 'createUser'])
        ->middleware('role:administrado_routes')
        ->name('auth.register');
});

// Agrupación de rutas protegidas con autenticación
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    /*
     * [Rutas para Administradores]
     * 
     * Endpoints exclusivos para usuarios con rol "administrador".
     * Incluye gestión de usuarios y administración de datos de referencia.
     */
    Route::middleware('role:admin_routes')->group(function () {
        // Gestión de usuarios
        Route::apiResource('usuarios', UserController::class)
            ->only(['index'])
            ->names([
                'index' => 'usuarios.index',
            ]);

        // Gestión de caché de datos de referencia
        Route::post('/reference-data/clear-cache', [ReferenceDataController::class, 'clearReferenceCache'])
            ->name('reference-data.clear-cache');
    });

    /*
     * [Rutas para Administrados]
     * 
     * Endpoints exclusivos para usuarios con rol "administrado".
     * Principalmente para registrar nuevas denuncias.
     */
    Route::middleware('role:administrado_routes')->group(function () {
        // Registro de denuncias
        Route::post('/denuncias', [ComplaintController::class, 'storeComplaint'])
            ->name('denuncias.store');
    });

    /*
     * [Rutas compartidas entre Administradores y Funcionarios]
     * 
     * Endpoints accesibles para usuarios con rol "administrador" o "funcionario".
     * Relacionados con el procesamiento de denuncias.
     */
    Route::middleware('role:shared_routes')->group(function () {
        // Procesamiento de denuncias
        Route::put('/denuncias/{complaintId}/procesar', [ComplaintController::class, 'processComplaint'])
            ->name('denuncias.process');
    });

    /*
     * [Rutas accesibles para todos los usuarios autenticados]
     * 
     * La lógica de control de acceso se implementa a nivel de controlador:
     * - Los "administrados" solo ven sus propias denuncias
     * - Los "administradores" y "funcionarios" ven todas las denuncias
     */

    // Consulta de denuncias (filtrado por rol en el controlador)
    Route::get('/denuncias', [ComplaintController::class, 'listComplaints'])
        ->name('denuncias.list');

    Route::get('/denuncias/{complaintId}', [ComplaintController::class, 'showComplaint'])
        ->name('denuncias.show');

    /*
     * [Rutas de datos de referencia]
     * 
     * Endpoints para obtener datos de catálogos y referencias.
     * Accesibles para todos los usuarios autenticados sin importar su rol.
     */

    // Obtener todos los datos de referencia
    Route::get('/reference-data', [ReferenceDataController::class, 'getAllReferences'])
        ->name('reference-data.all');

    // Rutas específicas para cada tipo de dato de referencia
    Route::prefix('reference-data')->name('reference-data.')->group(function () {
        Route::get('/airlines', [ReferenceDataController::class, 'getAirlines'])
            ->name('airlines');

        Route::get('/airports', [ReferenceDataController::class, 'getAirports'])
            ->name('airports');

        Route::get('/complaint-status', [ReferenceDataController::class, 'getComplaintStatus'])
            ->name('complaint-status');

        Route::get('/countries', [ReferenceDataController::class, 'getCountries'])
            ->name('countries');

        Route::get('/document-types', [ReferenceDataController::class, 'getDocumentTypes'])
            ->name('document-types');

        Route::get('/flight-types', [ReferenceDataController::class, 'getFlightTypes'])
            ->name('flight-types');

        Route::get('/motives', [ReferenceDataController::class, 'getMotives'])
            ->name('motives');

        Route::get('/roles', [ReferenceDataController::class, 'getRoles'])
            ->name('roles');

        Route::get('/airports-by-airline/{airlineId}', [ReferenceDataController::class, 'getAirportsByAirline'])
            ->name('airports-by-airline');
    });
});
