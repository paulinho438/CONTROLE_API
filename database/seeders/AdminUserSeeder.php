<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Verificar se o usuário admin já existe
        $adminExists = DB::table('users')->where('login', 'admin')->exists();
        
        if (!$adminExists) {
            // Criar usuário admin usando DB::insert para evitar problemas com timestamps
            $now = DB::raw('GETDATE()');
            $userId = DB::table('users')->insertGetId([
                'login' => 'admin',
                'nome_completo' => 'Administrador',
                'email' => 'admin@rialma.com.br',
                'password' => Hash::make('admin123'),
                'status' => 'A', // 'A' = Ativo conforme a migration
                'created_at' => $now,
                'updated_at' => $now
            ]);
        } else {
            $userId = DB::table('users')->where('login', 'admin')->value('id');
        }

        // Buscar grupo Administrador
        $adminGroup = DB::table('permgroups')->where('name', 'Administrador')->first();

        if ($adminGroup) {
            // Associar usuário ao grupo Administrador
            $exists = DB::table('permgroup_user')
                ->where('user_id', $userId)
                ->where('permgroup_id', $adminGroup->id)
                ->exists();
            
            if (!$exists) {
                DB::table('permgroup_user')->insert([
                    'user_id' => $userId,
                    'permgroup_id' => $adminGroup->id
                ]);
            }
            
            $this->command->info('Usuário admin criado com sucesso!');
            $this->command->info('Login: admin');
            $this->command->info('Senha: admin123');
            $this->command->info('Usuário associado ao grupo Administrador com todas as permissões!');
        } else {
            $this->command->error('Grupo "Administrador" não encontrado! Execute primeiro o PermissionSeeder.');
        }
    }
}

