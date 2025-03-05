<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Configurar rutas de prueba para cada tipo de ruta
        Route::middleware('role:admin_routes')->get('/test-admin', function () {
            return 'Admin route access granted';
        });

        Route::middleware('role:administrado_routes')->get('/test-administrado', function () {
            return 'Administrado route access granted';
        });

        Route::middleware('role:shared_routes')->get('/test-shared', function () {
            return 'Shared route access granted';
        });
    }

    /** @test */
    public function unauthenticated_user_cannot_access_routes()
    {
        $response = $this->get('/test-admin');
        $response->assertStatus(401);
        $response->assertJson(['message' => 'No autenticado.']);
    }

    /** @test */
    public function admin_can_access_admin_routes()
    {
        $adminRole = Role::where('role_name', 'administrador')->first();
        $admin = User::factory()->create(['role_id' => $adminRole->role_id]);

        $this->actingAs($admin)
             ->get('/test-admin')
             ->assertStatus(200)
             ->assertSee('Admin route access granted');
    }

    /** @test */
    public function funcionario_can_access_shared_routes()
    {
        $funcionarioRole = Role::where('role_name', 'funcionario')->first();
        $funcionario = User::factory()->create(['role_id' => $funcionarioRole->role_id]);

        $this->actingAs($funcionario)
             ->get('/test-shared')
             ->assertStatus(200)
             ->assertSee('Shared route access granted');
    }

    /** @test */
    public function administrado_cannot_access_admin_routes()
    {
        $administradoRole = Role::where('role_name', 'administrado')->first();
        $administrado = User::factory()->create(['role_id' => $administradoRole->role_id]);

        $this->actingAs($administrado)
             ->get('/test-admin')
             ->assertStatus(403)
             ->assertJson(['message' => 'No autorizado.']);
    }

    /** @test */
    public function roles_have_specific_route_access_limitations()
    {
        $roles = [
            'administrador' => ['/test-admin', '/test-shared'],
            'funcionario' => ['/test-shared'],
            'administrado' => ['/test-administrado']
        ];

        foreach ($roles as $roleName => $allowedRoutes) {
            $role = Role::where('role_name', $roleName)->first();
            $user = User::factory()->create(['role_id' => $role->role_id]);

            foreach (['/test-admin', '/test-shared', '/test-administrado'] as $route) {
                $this->actingAs($user)
                     ->get($route)
                     ->assertStatus(in_array($route, $allowedRoutes) ? 200 : 403);
            }
        }
    }
}