<?php

namespace Tests\Feature;

use App\Models\Entrada;
use App\Models\Grupo;
use App\Models\Material;
use App\Models\Patio;
use App\Models\Fornecedor;
use App\Models\NotaFiscal;
use App\Models\UnidadeMedida;
use App\Models\Colaborador;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\Feature\TestCaseWithPermissions;

class EntradaTest extends TestCase
{
    use RefreshDatabase, TestCaseWithPermissions;

    private $user;
    private $token;
    private $grupo;
    private $material;
    private $patio;
    private $fornecedor;
    private $notaFiscal;
    private $unidade;
    private $colaborador;

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

        // Login
        $loginResponse = $this->postJson('/api/auth/login', [
            'login' => 'admin',
            'password' => 'admin123'
        ]);
        $this->token = $loginResponse->json('token');

        // Criar dados relacionados usando DB::raw para SQL Server
        $grupoId = DB::table('grupos')->insertGetId([
            'nome' => 'Grupo Teste',
            'data_cadastro' => now()->format('Y-m-d'),
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $this->grupo = Grupo::find($grupoId);
        
        $patioId = DB::table('patios')->insertGetId([
            'nome' => 'Pátio Teste',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $this->patio = Patio::find($patioId);
        
        $fornecedorId = DB::table('fornecedores')->insertGetId([
            'razao_social' => 'Fornecedor Teste',
            'cnpj_cpf' => '12345678901',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $this->fornecedor = Fornecedor::find($fornecedorId);
        
        $unidadeId = DB::table('unidades_medida')->insertGetId([
            'unidade' => 'UN',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $this->unidade = UnidadeMedida::find($unidadeId);
        
        $colaboradorId = DB::table('colaboradores')->insertGetId([
            'nome_completo' => 'Colaborador Teste',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $this->colaborador = Colaborador::find($colaboradorId);
        
        $materialId = DB::table('materiais')->insertGetId([
            'grupo_id' => $this->grupo->id,
            'nome' => 'Material Teste',
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $this->material = Material::find($materialId);

        $notaId = DB::table('notas_fiscais')->insertGetId([
            'fornecedor_id' => $this->fornecedor->id,
            'numero_nota' => '12345',
            'data_emissao' => now()->format('Y-m-d'),
            'data_recebimento' => now()->format('Y-m-d'),
            'valor' => 1000.00,
            'created_at' => DB::raw('GETDATE()'),
            'updated_at' => DB::raw('GETDATE()')
        ]);
        $this->notaFiscal = NotaFiscal::find($notaId);
    }

    public function test_criar_entrada()
    {
        $data = [
            'grupo_id' => $this->grupo->id,
            'material_id' => $this->material->id,
            'patio_id' => $this->patio->id,
            'fornecedor_id' => $this->fornecedor->id,
            'nota_fiscal_id' => $this->notaFiscal->id,
            'quantidade' => 10,
            'valor' => 500.00,
            'data_recebimento' => now()->format('Y-m-d'),
            'unidade_medida_id' => $this->unidade->id,
            'responsavel_colaborador_id' => $this->colaborador->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/entradas', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'quantidade',
                    'valor'
                ]
            ]);

        $this->assertDatabaseHas('entradas', [
            'material_id' => $this->material->id,
            'quantidade' => 10,
            'valor' => 500.00
        ]);
    }

    public function test_calculo_valor_total_automatico()
    {
        $valorTotal = 1510.00;
        $data = [
            'grupo_id' => $this->grupo->id,
            'material_id' => $this->material->id,
            'patio_id' => $this->patio->id,
            'fornecedor_id' => $this->fornecedor->id,
            'nota_fiscal_id' => $this->notaFiscal->id,
            'quantidade' => 20,
            'valor' => $valorTotal,
            'data_recebimento' => now()->format('Y-m-d'),
            'unidade_medida_id' => $this->unidade->id,
            'responsavel_colaborador_id' => $this->colaborador->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/entradas', $data);

        $response->assertStatus(200);
        
        $entrada = Entrada::latest()->first();
        $this->assertEquals($valorTotal, $entrada->valor);
    }

    public function test_validacao_campos_obrigatorios()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/entradas', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'grupo_id',
                'material_id',
                'patio_id',
                'quantidade',
                'unidade_medida_id'
            ]);
    }

    public function test_validacao_quantidade_minima()
    {
        $data = [
            'grupo_id' => $this->grupo->id,
            'material_id' => $this->material->id,
            'patio_id' => $this->patio->id,
            'fornecedor_id' => $this->fornecedor->id,
            'nota_fiscal_id' => $this->notaFiscal->id,
            'quantidade' => 0, // Quantidade inválida
            'valor_unitario' => 50.00,
            'data_recebimento' => now()->format('Y-m-d'),
            'unidade_medida_id' => $this->unidade->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/entradas', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['quantidade']);
    }
}

