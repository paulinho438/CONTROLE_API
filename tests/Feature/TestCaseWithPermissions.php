<?php

namespace Tests\Feature;

use App\Models\Permgroup;
use App\Models\Permitem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

trait TestCaseWithPermissions
{
    /**
     * Cria ou obtém o grupo Administrador com todas as permissões
     */
    protected function setupAdminGroup(): Permgroup
    {
        // Verificar se o grupo já existe
        $adminGroup = Permgroup::where('name', 'Administrador')->first();
        
        if (!$adminGroup) {
            // Criar grupo usando DB::table para SQL Server
            $groupId = DB::table('permgroups')->insertGetId([
                'name' => 'Administrador',
                'created_at' => DB::raw('GETDATE()'),
                'updated_at' => DB::raw('GETDATE()')
            ]);
            $adminGroup = Permgroup::find($groupId);
        }

        // Criar permissões básicas se não existirem
        $permissions = [
            ['slug' => 'grupos.view', 'name' => 'Visualizar Grupos', 'group' => 'Cadastros'],
            ['slug' => 'grupos.create', 'name' => 'Criar Grupos', 'group' => 'Cadastros'],
            ['slug' => 'grupos.edit', 'name' => 'Editar Grupos', 'group' => 'Cadastros'],
            ['slug' => 'grupos.delete', 'name' => 'Excluir Grupos', 'group' => 'Cadastros'],
            ['slug' => 'entradas.view', 'name' => 'Visualizar Entradas', 'group' => 'Movimentações'],
            ['slug' => 'entradas.create', 'name' => 'Criar Entradas', 'group' => 'Movimentações'],
            ['slug' => 'fornecedores.view', 'name' => 'Visualizar Fornecedores', 'group' => 'Cadastros'],
            ['slug' => 'fornecedores.create', 'name' => 'Criar Fornecedores', 'group' => 'Cadastros'],
            ['slug' => 'dashboard.view', 'name' => 'Visualizar Dashboard', 'group' => 'Dashboard'],
        ];

        $permissionIds = [];
        foreach ($permissions as $perm) {
            $existing = Permitem::where('slug', $perm['slug'])->first();
            if (!$existing) {
                $permId = DB::table('permitems')->insertGetId([
                    'slug' => $perm['slug'],
                    'name' => $perm['name'],
                    'group' => $perm['group'],
                    'created_at' => DB::raw('GETDATE()'),
                    'updated_at' => DB::raw('GETDATE()')
                ]);
                $permissionIds[] = $permId;
            } else {
                $permissionIds[] = $existing->id;
            }
        }

        // Obter todas as permissões existentes
        $allPermissions = Permitem::all();

        // Associar todas as permissões ao grupo Administrador
        if ($allPermissions->count() > 0) {
            $adminGroup->items()->sync($allPermissions->pluck('id')->toArray());
        } elseif (count($permissionIds) > 0) {
            $adminGroup->items()->sync($permissionIds);
        }

        return $adminGroup;
    }

    /**
     * Associa um usuário ao grupo Administrador
     */
    protected function assignAdminGroup(User $user): void
    {
        $adminGroup = $this->setupAdminGroup();
        $user->groups()->sync([$adminGroup->id]);
    }
}

