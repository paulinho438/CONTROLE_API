<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar usuário admin para testes usando DB::raw para SQL Server
        DB::table('users')->insert([
            'login' => 'admin',
            'nome_completo' => 'Administrador',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin123'),
            'status' => 'A',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
    }

    public function test_login_success()
    {
        $response = $this->postJson('/api/auth/login', [
            'login' => 'admin',
            'password' => 'admin123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'login',
                    'nome_completo',
                    'email'
                ]
            ]);
    }

    public function test_login_failure_invalid_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'login' => 'admin',
            'password' => 'wrong_password'
        ]);

        // BusinessException retorna 422, não 401
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Usuário ou senha inválidos.'
            ]);
    }

    public function test_login_validation_required_fields()
    {
        $response = $this->postJson('/api/auth/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_validate_token_success()
    {
        $loginResponse = $this->postJson('/api/auth/login', [
            'login' => 'admin',
            'password' => 'admin123'
        ]);

        $token = $loginResponse->json('token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/auth/validate');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'login',
                    'nome_completo'
                ]
            ]);
    }

    public function test_logout_success()
    {
        $loginResponse = $this->postJson('/api/auth/login', [
            'login' => 'admin',
            'password' => 'admin123'
        ]);

        $token = $loginResponse->json('token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['ok' => true]);
    }
}

