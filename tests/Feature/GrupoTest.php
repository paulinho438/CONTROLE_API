<?php

namespace Tests\Feature;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\Feature\TestCaseWithPermissions;

class GrupoTest extends TestCase
{
    use RefreshDatabase, TestCaseWithPermissions;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar usuário admin usando DB::raw para SQL Server
        $userId = DB::table('users')->insertGetId([
            'login' => 'admin',
            'nome_completo' => 'Administrador',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin123'),
            'status' => 'A',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $this->user = User::find($userId);

        // Associar usuário ao grupo Administrador (que tem todas as permissões)
        $this->assignAdminGroup($this->user);

        // Login e obter token
        $loginResponse = $this->postJson('/api/auth/login', [
            'login' => 'admin',
            'password' => 'admin123'
        ]);

        $this->token = $loginResponse->json('token');
    }

    public function test_listar_grupos()
    {
        // Criar grupos de teste usando DB::raw para SQL Server
        DB::table('grupos')->insert([
            ['nome' => 'Grupo 1', 'data_cadastro' => now()->format('Y-m-d'), 'created_at' => DB::raw('GETDATE()'), 'updated_at' => DB::raw('GETDATE()')],
            ['nome' => 'Grupo 2', 'data_cadastro' => now()->format('Y-m-d'), 'created_at' => DB::raw('GETDATE()'), 'updated_at' => DB::raw('GETDATE()')]
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/grupos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'nome',
                        'data_cadastro'
                    ]
                ]
            ]);

        $this->assertCount(2, $response->json('data'));
    }

    public function test_criar_grupo()
    {
        $data = [
            'nome' => 'Novo Grupo',
            'data_cadastro' => now()->format('Y-m-d')
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/grupos', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'nome',
                    'data_cadastro'
                ]
            ])
            ->assertJson([
                'data' => [
                    'nome' => 'Novo Grupo'
                ]
            ]);

        $this->assertDatabaseHas('grupos', [
            'nome' => 'Novo Grupo'
        ]);
    }

    public function test_criar_grupo_validation_required()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/grupos', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome']);
    }

    public function test_atualizar_grupo()
    {
        $grupoId = DB::table('grupos')->insertGetId([
            'nome' => 'Grupo Original',
            'data_cadastro' => now()->format('Y-m-d'),
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $grupo = Grupo::find($grupoId);

        $data = [
            'nome' => 'Grupo Atualizado',
            'data_cadastro' => now()->format('Y-m-d')
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson("/api/grupos/{$grupo->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'nome' => 'Grupo Atualizado'
                ]
            ]);

        $this->assertDatabaseHas('grupos', [
            'id' => $grupo->id,
            'nome' => 'Grupo Atualizado'
        ]);
    }

    public function test_excluir_grupo()
    {
        $grupoId = DB::table('grupos')->insertGetId([
            'nome' => 'Grupo para Excluir',
            'data_cadastro' => now()->format('Y-m-d'),
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $grupo = Grupo::find($grupoId);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/grupos/{$grupo->id}");

        $response->assertStatus(200)
            ->assertJson(['ok' => true]);

        $this->assertDatabaseMissing('grupos', [
            'id' => $grupo->id
        ]);
    }
}

