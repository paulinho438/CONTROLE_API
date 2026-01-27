<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permitem;
use App\Models\Permgroup;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Criar permissões organizadas por grupos
        $permissions = [
            // Dashboard
            ['name' => 'Visualizar Dashboard', 'slug' => 'dashboard.view', 'group' => 'Dashboard'],
            
            // Cadastros - Grupos
            ['name' => 'Visualizar Grupos', 'slug' => 'grupos.view', 'group' => 'Cadastros'],
            ['name' => 'Criar Grupos', 'slug' => 'grupos.create', 'group' => 'Cadastros'],
            ['name' => 'Editar Grupos', 'slug' => 'grupos.edit', 'group' => 'Cadastros'],
            ['name' => 'Excluir Grupos', 'slug' => 'grupos.delete', 'group' => 'Cadastros'],
            
            // Cadastros - Materiais
            ['name' => 'Visualizar Materiais', 'slug' => 'materiais.view', 'group' => 'Cadastros'],
            ['name' => 'Criar Materiais', 'slug' => 'materiais.create', 'group' => 'Cadastros'],
            ['name' => 'Editar Materiais', 'slug' => 'materiais.edit', 'group' => 'Cadastros'],
            ['name' => 'Excluir Materiais', 'slug' => 'materiais.delete', 'group' => 'Cadastros'],
            
            // Cadastros - Pátios
            ['name' => 'Visualizar Pátios', 'slug' => 'patios.view', 'group' => 'Cadastros'],
            ['name' => 'Criar Pátios', 'slug' => 'patios.create', 'group' => 'Cadastros'],
            ['name' => 'Editar Pátios', 'slug' => 'patios.edit', 'group' => 'Cadastros'],
            ['name' => 'Excluir Pátios', 'slug' => 'patios.delete', 'group' => 'Cadastros'],
            
            // Cadastros - Fornecedores
            ['name' => 'Visualizar Fornecedores', 'slug' => 'fornecedores.view', 'group' => 'Cadastros'],
            ['name' => 'Criar Fornecedores', 'slug' => 'fornecedores.create', 'group' => 'Cadastros'],
            ['name' => 'Editar Fornecedores', 'slug' => 'fornecedores.edit', 'group' => 'Cadastros'],
            ['name' => 'Excluir Fornecedores', 'slug' => 'fornecedores.delete', 'group' => 'Cadastros'],
            
            // Cadastros - Unidades de Medida
            ['name' => 'Visualizar Unidades de Medida', 'slug' => 'unidades-medida.view', 'group' => 'Cadastros'],
            ['name' => 'Criar Unidades de Medida', 'slug' => 'unidades-medida.create', 'group' => 'Cadastros'],
            ['name' => 'Editar Unidades de Medida', 'slug' => 'unidades-medida.edit', 'group' => 'Cadastros'],
            ['name' => 'Excluir Unidades de Medida', 'slug' => 'unidades-medida.delete', 'group' => 'Cadastros'],
            
            // Cadastros - Colaboradores
            ['name' => 'Visualizar Colaboradores', 'slug' => 'colaboradores.view', 'group' => 'Cadastros'],
            ['name' => 'Criar Colaboradores', 'slug' => 'colaboradores.create', 'group' => 'Cadastros'],
            ['name' => 'Editar Colaboradores', 'slug' => 'colaboradores.edit', 'group' => 'Cadastros'],
            ['name' => 'Excluir Colaboradores', 'slug' => 'colaboradores.delete', 'group' => 'Cadastros'],
            
            // Cadastros - Notas Fiscais
            ['name' => 'Visualizar Notas Fiscais', 'slug' => 'notas-fiscais.view', 'group' => 'Cadastros'],
            ['name' => 'Criar Notas Fiscais', 'slug' => 'notas-fiscais.create', 'group' => 'Cadastros'],
            ['name' => 'Editar Notas Fiscais', 'slug' => 'notas-fiscais.edit', 'group' => 'Cadastros'],
            ['name' => 'Excluir Notas Fiscais', 'slug' => 'notas-fiscais.delete', 'group' => 'Cadastros'],
            
            // Movimentações - Entradas
            ['name' => 'Visualizar Entradas', 'slug' => 'entradas.view', 'group' => 'Movimentações'],
            ['name' => 'Criar Entradas', 'slug' => 'entradas.create', 'group' => 'Movimentações'],
            ['name' => 'Editar Entradas', 'slug' => 'entradas.edit', 'group' => 'Movimentações'],
            ['name' => 'Excluir Entradas', 'slug' => 'entradas.delete', 'group' => 'Movimentações'],
            
            // Movimentações - Saídas
            ['name' => 'Visualizar Saídas', 'slug' => 'saidas.view', 'group' => 'Movimentações'],
            ['name' => 'Criar Saídas', 'slug' => 'saidas.create', 'group' => 'Movimentações'],
            ['name' => 'Editar Saídas', 'slug' => 'saidas.edit', 'group' => 'Movimentações'],
            ['name' => 'Excluir Saídas', 'slug' => 'saidas.delete', 'group' => 'Movimentações'],
            
            // Movimentações - Transferências
            ['name' => 'Visualizar Transferências', 'slug' => 'transferencias.view', 'group' => 'Movimentações'],
            ['name' => 'Criar Transferências', 'slug' => 'transferencias.create', 'group' => 'Movimentações'],
            ['name' => 'Editar Transferências', 'slug' => 'transferencias.edit', 'group' => 'Movimentações'],
            ['name' => 'Excluir Transferências', 'slug' => 'transferencias.delete', 'group' => 'Movimentações'],
            
            // Movimentações - Balanço
            ['name' => 'Visualizar Balanço', 'slug' => 'balanco.view', 'group' => 'Movimentações'],
            
            // Previsões - Planejamento
            ['name' => 'Visualizar Previsões', 'slug' => 'previsoes.view', 'group' => 'Previsões'],
            ['name' => 'Criar Previsões', 'slug' => 'previsoes.create', 'group' => 'Previsões'],
            ['name' => 'Editar Previsões', 'slug' => 'previsoes.edit', 'group' => 'Previsões'],
            ['name' => 'Excluir Previsões', 'slug' => 'previsoes.delete', 'group' => 'Previsões'],
            
            // Previsões - Relatórios
            ['name' => 'Visualizar Previsão Geral', 'slug' => 'previsao-geral.view', 'group' => 'Previsões'],
            ['name' => 'Visualizar Previsão por Pátio', 'slug' => 'previsao-por-patio.view', 'group' => 'Previsões'],
            
            // Administração - Permissões
            ['name' => 'Visualizar Permissões', 'slug' => 'permissoes.view', 'group' => 'Administração'],
            ['name' => 'Criar Permissões', 'slug' => 'permissoes.create', 'group' => 'Administração'],
            ['name' => 'Editar Permissões', 'slug' => 'permissoes.edit', 'group' => 'Administração'],
            ['name' => 'Excluir Permissões', 'slug' => 'permissoes.delete', 'group' => 'Administração'],
            
            // Administração - Usuários
            ['name' => 'Visualizar Usuários', 'slug' => 'usuarios.view', 'group' => 'Administração'],
            ['name' => 'Criar Usuários', 'slug' => 'usuarios.create', 'group' => 'Administração'],
            ['name' => 'Editar Usuários', 'slug' => 'usuarios.edit', 'group' => 'Administração'],
            ['name' => 'Excluir Usuários', 'slug' => 'usuarios.delete', 'group' => 'Administração'],
        ];

        // Criar permissões usando DB::insert para evitar problemas com timestamps
        $now = DB::raw('GETDATE()');
        foreach ($permissions as $permission) {
            $exists = DB::table('permitems')->where('slug', $permission['slug'])->exists();
            if (!$exists) {
                DB::table('permitems')->insert([
                    'name' => $permission['name'],
                    'slug' => $permission['slug'],
                    'group' => $permission['group'],
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }

        // Criar grupo de permissões "Administrador"
        $adminGroupExists = DB::table('permgroups')->where('name', 'Administrador')->exists();
        if (!$adminGroupExists) {
            $adminGroupId = DB::table('permgroups')->insertGetId([
                'name' => 'Administrador',
                'created_at' => $now,
                'updated_at' => $now
            ]);
        } else {
            $adminGroupId = DB::table('permgroups')->where('name', 'Administrador')->value('id');
        }

        // Associar todas as permissões ao grupo Administrador
        $allPermissionIds = DB::table('permitems')->pluck('id');
        foreach ($allPermissionIds as $permissionId) {
            $exists = DB::table('permgroup_permitem')
                ->where('permgroup_id', $adminGroupId)
                ->where('permitem_id', $permissionId)
                ->exists();
            if (!$exists) {
                DB::table('permgroup_permitem')->insert([
                    'permgroup_id' => $adminGroupId,
                    'permitem_id' => $permissionId
                ]);
            }
        }

        $this->command->info('Permissões criadas com sucesso!');
        $this->command->info('Grupo "Administrador" criado com todas as permissões!');
    }
}

