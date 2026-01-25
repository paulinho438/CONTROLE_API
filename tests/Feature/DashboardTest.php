<?php

namespace Tests\Feature;

use App\Models\Entrada;
use App\Models\Saida;
use App\Models\NotaFiscal;
use App\Models\Material;
use App\Models\Grupo;
use App\Models\Patio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\Feature\TestCaseWithPermissions;

class DashboardTest extends TestCase
{
    use RefreshDatabase, TestCaseWithPermissions;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        
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

        $loginResponse = $this->postJson('/api/auth/login', [
            'login' => 'admin',
            'password' => 'admin123'
        ]);
        $this->token = $loginResponse->json('token');
    }

    public function test_resumo_geral()
    {
        // Criar dados de teste usando DB::raw para SQL Server
        $grupoId = DB::table('grupos')->insertGetId([
            'nome' => 'Grupo Teste',
            'data_cadastro' => now()->format('Y-m-d'),
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $grupo = Grupo::find($grupoId);
        
        $patioId = DB::table('patios')->insertGetId([
            'nome' => 'Pátio Teste',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $patio = Patio::find($patioId);
        
        $unidadeId = DB::table('unidades_medida')->insertGetId([
            'unidade' => 'UN',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $unidade = \App\Models\UnidadeMedida::find($unidadeId);
        
        $materialId = DB::table('materiais')->insertGetId([
            'grupo_id' => $grupo->id,
            'nome' => 'Material Teste',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $material = Material::find($materialId);
        
        $fornecedorId = DB::table('fornecedores')->insertGetId([
            'razao_social' => 'Fornecedor',
            'cnpj_cpf' => '123',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $fornecedor = \App\Models\Fornecedor::find($fornecedorId);
        
        $notaId = DB::table('notas_fiscais')->insertGetId([
            'fornecedor_id' => $fornecedor->id,
            'numero_nota' => '123',
            'data_emissao' => now()->format('Y-m-d'),
            'data_recebimento' => now()->format('Y-m-d'),
            'valor' => 1000,
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $nota = NotaFiscal::find($notaId);
        
        $colaboradorId = DB::table('colaboradores')->insertGetId([
            'nome_completo' => 'Colab',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $colaborador = \App\Models\Colaborador::find($colaboradorId);

        DB::table('entradas')->insert([
            'grupo_id' => $grupo->id,
            'material_id' => $material->id,
            'patio_id' => $patio->id,
            'fornecedor_id' => $fornecedor->id,
            'nota_fiscal_id' => $nota->id,
            'quantidade' => 10,
            'valor' => 500,
            'data_recebimento' => now()->format('Y-m-d'),
            'unidade_medida_id' => $unidade->id,
            'responsavel_colaborador_id' => $colaborador->id,
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);

        DB::table('saidas')->insert([
            'grupo_id' => $grupo->id,
            'material_id' => $material->id,
            'patio_id' => $patio->id,
            'quantidade' => 5,
            'data_saida' => now()->format('Y-m-d'),
            'unidade_medida_id' => $unidade->id,
            'responsavel_colaborador_id' => $colaborador->id,
            'tipo_movimentacao' => 'Saída',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/dashboard/resumo-geral');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_entradas',
                'total_saidas',
                'total_notas_fiscais',
                'total_materiais'
            ]);

        $data = $response->json();
        $this->assertGreaterThanOrEqual(1, $data['total_entradas']);
        $this->assertGreaterThanOrEqual(1, $data['total_saidas']);
        $this->assertGreaterThanOrEqual(1, $data['total_notas_fiscais']);
        $this->assertGreaterThanOrEqual(1, $data['total_materiais']);
    }

    public function test_resumo_estoque_com_filtros()
    {
        $grupo1Id = \Illuminate\Support\Facades\DB::table('grupos')->insertGetId([
            'nome' => 'Grupo 1',
            'data_cadastro' => now()->format('Y-m-d'),
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $grupo1 = Grupo::find($grupo1Id);
        
        $grupo2Id = \Illuminate\Support\Facades\DB::table('grupos')->insertGetId([
            'nome' => 'Grupo 2',
            'data_cadastro' => now()->format('Y-m-d'),
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $grupo2 = Grupo::find($grupo2Id);
        
        $patio1Id = \Illuminate\Support\Facades\DB::table('patios')->insertGetId([
            'nome' => 'Pátio 1',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $patio1 = Patio::find($patio1Id);
        
        $patio2Id = \Illuminate\Support\Facades\DB::table('patios')->insertGetId([
            'nome' => 'Pátio 2',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $patio2 = Patio::find($patio2Id);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/dashboard/resumo-estoque', [
            'grupos' => [$grupo1->id],
            'patios' => [$patio1->id]
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'grupos' => [
                    '*' => [
                        'id',
                        'nome',
                        'materiais' => [
                            '*' => [
                                'id',
                                'material',
                                'entradas_por_patio',
                                'total_recebido'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_balanco_com_filtros()
    {
        $grupoId = \Illuminate\Support\Facades\DB::table('grupos')->insertGetId([
            'nome' => 'Grupo Teste',
            'data_cadastro' => now()->format('Y-m-d'),
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $grupo = Grupo::find($grupoId);
        
        $unidadeId = DB::table('unidades_medida')->insertGetId([
            'unidade' => 'UN',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $unidade = \App\Models\UnidadeMedida::find($unidadeId);
        
        $materialId = DB::table('materiais')->insertGetId([
            'grupo_id' => $grupo->id,
            'nome' => 'Material Teste',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $material = Material::find($materialId);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/dashboard/balanco', [
            'grupos' => [$grupo->id],
            'materiais' => [$material->id]
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'grupos' => [
                    '*' => [
                        'id',
                        'nome',
                        'materiais' => [
                            '*' => [
                                'id',
                                'material',
                                'entradas_por_patio',
                                'total_recebido'
                            ]
                        ]
                    ]
                ]
            ]);
    }
}

