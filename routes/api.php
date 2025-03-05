<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\UsuarioController;
// use App\Http\Controllers\DenunciaController;

/*
 ====================================================================
 Ejemplo de Rutas API para consumo exclusivo desde Angular 19
 ====================================================================

 Este archivo define ejemplos de endpoints RESTful que el frontend en Angular
 consumirá para interactuar con el backend en Laravel.

 La API se encuentra versionada (v1) para facilitar la evolución en el futuro.
 Se emplean middleware para:
   - Verificar que la solicitud provenga de un usuario autenticado (por ejemplo, auth:sanctum).
   - Validar que el usuario posea el rol requerido para acceder al recurso.

 Las reglas del negocio son:
   Usuarios con rol "administrado" (clientes/empresas) solo pueden:
       - Registrarse a través del endpoint público de registro.
       - Listar y crear sus propias denuncias.

   Usuarios con rol "funcionario" (trabajadores del sistema) pueden:
       - Listar todas las denuncias.
       - Procesar denuncias (agregar notas, cambiar estado, etc.)

   Usuarios con rol "administrador" (administradores del sistema) pueden:
       - Listar todos los usuarios.
       - Listar y procesar todas las denuncias.

 Para la verificación de roles se utiliza un middleware personalizado llamado 'role'
 que, en este ejemplo, utiliza las siguientes configuraciones:

   - "admin_routes": Acceso exclusivo para administradores.
   - "administrado_routes": Acceso exclusivo para usuarios administrados.
   - "shared_routes": Acceso compartido para administradores y funcionarios.

 Nota: Los siguientes ejemplos están comentados; se recomienda ajustarlos según las
 necesidades específicas de la API y del frontend.

 ====================================================================
 Ejemplos de Endpoints
 ====================================================================
*/

 // =========================
 // RUTAS PÚBLICAS DE AUTENTICACIÓN
 // =========================

 // Endpoint para iniciar sesión. Devuelve un token para autenticación.
 Route::post('/v1/login', [AuthController::class, 'login'])
     ->name('login');

 // Endpoint para registrar un nuevo usuario.
 // Importante: Solo se permite el registro de usuarios con rol "administrado".
 Route::post('/v1/registro', [AuthController::class, 'registro'])
     ->middleware('role:administrado_routes')
     ->name('registro');

 // =========================
 // RUTAS PROTEGIDAS PARA ADMINISTRADORES
 // =========================

 // Listado de todos los usuarios (solo accesible para administradores).
 Route::middleware(['auth:sanctum', 'role:admin_routes'])->group(function () {
     Route::get('/v1/usuarios', [UsuarioController::class, 'index'])
         ->name('usuarios.index');
 });

 // Gestión de denuncias: listado y procesamiento.
 // Los administradores pueden ver todas las denuncias y procesarlas.
 Route::middleware(['auth:sanctum', 'role:admin_routes'])->group(function () {
    //  Route::get('/v1/denuncias', [DenunciaController::class, 'index'])
    //      ->name('denuncias.index');
    //  Route::put('/v1/denuncias/{denuncia}/procesar', [DenunciaController::class, 'procesar'])
    //      ->name('denuncias.procesar');
 });

 // =========================
 // RUTAS PROTEGIDAS PARA USUARIOS ADMINISTRADOS
 // =========================

 // Permite a los usuarios administrados registrar nuevas denuncias
 // y consultar únicamente las denuncias propias.
 Route::middleware(['auth:sanctum', 'role:administrado_routes'])->group(function () {
    //  Route::post('/v1/denuncias', [DenunciaController::class, 'store'])
    //      ->name('denuncias.store');
    //  Route::get('/v1/mis-denuncias', [DenunciaController::class, 'misDenuncias'])
    //      ->name('denuncias.mis');
 });

 // =========================
 // RUTAS COMPARTIDAS PARA ADMINISTRADORES Y FUNCIONARIOS
 // =========================

 // Gestión del perfil del usuario (accesible para roles que comparten esta funcionalidad,
 // en este caso, administradores y funcionarios).
 Route::middleware(['auth:sanctum', 'role:shared_routes'])->group(function () {
    //  Route::get('/v1/perfil', [UsuarioController::class, 'perfil'])
    //      ->name('perfil.ver');
    //  Route::put('/v1/perfil', [UsuarioController::class, 'actualizarPerfil'])
    //      ->name('perfil.actualizar');
 });

/*
 ====================================================================
 Recomendaciones para el Consumo desde Angular:

 - Utilizar Angular HttpClient para interactuar con estos endpoints.
 - Manejar los tokens de autenticación en el cliente y pasarlos en las cabeceras de cada solicitud.
 - Implementar manejo de errores en Angular para procesar respuestas 401, 403 y 500.

 Estos ejemplos sirven como base para estructurar la API y pueden ser ampliados o modificados
 según las necesidades del negocio.
*/