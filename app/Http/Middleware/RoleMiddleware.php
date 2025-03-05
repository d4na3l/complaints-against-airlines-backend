<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RoleMiddleware
 *
 * Este middleware se encarga de verificar que el usuario autenticado posea uno de los roles permitidos
 * para acceder a una ruta específica.
 *
 * Se utiliza en el backend para proteger endpoints de la API consumida por Angular 19, asegurando que las reglas del negocio se cumplan.
 *
 * Las configuraciones actuales son:
 * - "admin_routes": Permite el acceso únicamente a usuarios con rol "administrador".
 * - "administrado_routes": Permite el acceso únicamente a usuarios con rol "administrado".
 * - "shared_routes": Permite el acceso a usuarios con rol "administrador" o "funcionario".
 *
 * Ejemplo de uso en rutas:
 *   Route::middleware('role:shared_routes')->group(function () {
 *       // Rutas accesibles para funcionarios y administradores
 *   });
 *
 * @package App\Http\Middleware
 */
class RoleMiddleware
{
    /**
     * Configuración de roles permitidos para cada tipo de ruta.
     *
     * - admin_routes: Acceso exclusivo para administradores.
     * - administrado_routes: Acceso exclusivo para usuarios administrados (registro y denuncias propias).
     * - shared_routes: Acceso compartido para administradores y funcionarios (listar y procesar denuncias).
     *
     * @var array
     */
    private const ROLE_PERMISSIONS = [
        'admin_routes'         => ['administrador'],
        'administrado_routes'  => ['administrado'],
        'shared_routes'        => ['administrador', 'funcionario']
    ];

    /**
     * Maneja la petición entrante y verifica que el usuario autenticado tenga alguno de los roles requeridos.
     *
     * @param  \Illuminate\Http\Request  $request  La solicitud entrante.
     * @param  \Closure  $next  La siguiente acción o middleware.
     * @param  string  $routeType  El tipo de ruta para el cual se requiere el rol.
     *                              Valores válidos: 'admin_routes', 'administrado_routes', 'shared_routes'.
     *                              Valor por defecto: 'admin_routes'.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $routeType = 'admin_routes'): Response
    {
        try {
            // Verificar autenticación
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'message' => 'No autenticado.',
                    'error'   => 'Debe iniciar sesión para acceder a este recurso.'
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Obtener la lista de roles permitidos para la ruta solicitada
            $allowedRoles = self::ROLE_PERMISSIONS[$routeType] ?? [];
            $userRole = $user->role->role_name ?? null;

            // Si el rol del usuario no está en la lista de roles permitidos, se deniega el acceso.
            if (!$userRole || !in_array($userRole, $allowedRoles)) {
                return response()->json([
                    'message' => 'No autorizado.',
                    'error'   => 'No tiene permisos para acceder a esta sección.',
                    'required_roles' => $allowedRoles
                ], Response::HTTP_FORBIDDEN);
            }
        } catch (\Exception $ex) {
            // Registrar el error para fines de depuración
            \Log::error('Error en middleware de rol: ' . $ex->getMessage(), [
                'user_id' => $user->id ?? null,
                'route'   => $request->path()
            ]);

            return response()->json([
                'message' => 'Error interno del servidor.',
                'error'   => config('app.debug') ? $ex->getMessage() : 'Ha ocurrido un error inesperado.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $next($request);
    }
}
